CREATE OR REPLACE FUNCTION set_incident_id()
RETURNS TRIGGER AS $$
BEGIN
   NEW.id = (select max(id) + 1 from incidents where application_id = NEW.application_id);
   IF (NEW.id IS NULL) THEN
       NEW.id = 1;
   END IF;
   RETURN NEW;
END;
$$ language 'plpgsql';


CREATE TRIGGER set_incident_id_insert BEFORE INSERT
    ON incidents FOR EACH ROW EXECUTE PROCEDURE
    set_incident_id();