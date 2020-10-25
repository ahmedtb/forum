<?php
namespace App\Models;

trait RecordActivity{

    protected static function bootRecordActivity()
    {
        if(auth()->guest())
                return;
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function($model) use ($event) {
                $model->recordActivity($event);
            });
        }

    }

    protected static function getActivitiesToRecord(){
        return ['created'];
    }


    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->user()->id
        ]);

    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return $event . '_' . $type;
    }

    public function activity(){
        return $this->morphMany(Activity::class,'subject');
    }
}
