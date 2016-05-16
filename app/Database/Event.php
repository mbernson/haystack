<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'application_id',
        'incident_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public $timestamps = false;

    public function hasStackTrace() {
        return $this->type == 'exception';
    }

    public function getStackTracePath($extension = '.txt') {
        return join('/', [
            'exceptions',
            $this->application_id,
            $this->id,
        ]).$extension;
    }

    public function saveStackTrace($stackTrace) {
        /** @var FilesystemInterface $filesystem */
        $filesystem = app(FilesystemInterface::class);
        return $filesystem->put($this->getStackTracePath(), $stackTrace);
    }

    public function saveStackTraceIfNeeded(Request $request) {
        if($request->has('stack_trace')) {
            return $this->saveStackTrace($request->get('stack_trace'));
        }
        return true;
    }

    public function getStackTrace() {
        /** @var FilesystemInterface $filesystem */
        $filesystem = app(FilesystemInterface::class);
        return $filesystem->read($this->getStackTracePath());
    }

    public function incident() {
        return $this->belongsTo(Incident::class);
    }

    public function createIncidentIfNeeded()
    {
        if(empty($this->title)) {
            return false;
        }
        if(is_null($this->application_id)) {
            return false;
        }
        $incident = Incident::where('title', $this->title)
            ->where('status', '!=', 'closed')
            ->first();
        if($incident) {
            $incident->occurences += 1;
        } else {
            $incident = new Incident();
            $incident->title = $this->title;
            $incident->application_id = $this->application_id;
            $incident->status = 'open';
        }
        $incident->save();
        $this->incident_id = $incident->getKey();
        return true;
    }

}
