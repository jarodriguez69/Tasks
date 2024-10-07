<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;

class TaskComponent extends Component
{

    public $tasks = [];
    public $id;
    public $title;
    public $description;
    public $modal= false;
    public $modalShare= false;
    public $isUpdating = false;
    public $miTarea = null;
    public $users = [];
    public $user_id;
    public $permiso;
    public function mount()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->users = User::where('id','!=', auth()->user()->id)->get();

    }
    public function getTasks()
    {
        $user = auth()->user();
        $misTareas = Task::where('user_id', auth()->user()->id)->get();
        $misSharedTasks = $user->sharedTasks()->get();
        return $misSharedTasks->merge($misTareas);
        
    }
    public function renderAllTasks()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function render()
    {
        return view('livewire.task-component');
    }

    public function clearFields()
    {
        $this->title='';
        $this->description='';
        $this->id='';
        $this->miTarea=null;
        $this->isUpdating = false;
    }

    public function openCreateModal(Task $task =null)
    {
        if($task)
        {
            $this->isUpdating = true;
            $this->miTarea=$task;
            $this->title=$task->title;
            $this->description=$task->description;
            $this->id=$task->id;
            
        }
        else
        {
            $this->clearFields();
        }
        
        $this->modal=true;
    }
    public function closeCreateModal()
    {
        $this->modal=false;
    }

    public function createorUpdateTask()
    {
        if ($this->miTarea->id)
        {
            $task = Task::find($this->miTarea->id);
            $task->update(
                [
                    'title' => $this->title,
                    'description' => $this->description
                ]);
        }
        else
        {
            $task= Task::create([
                    'user_id' => auth()->user()->id,
                    'title' => $this->title,
                    'description' => $this->description
                ]);
        }

        
        

    
        $this->clearFields();
        $this->modal=false;
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function editTask(Task $task)
    {

        $this->title = $task->title;
        $this->description = $task->description;
        $this->modal=true;
    }

    public function deleteTask(Task $task)
    {

        $task->delete();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function openShareModal(Task $task)
    {
        $this->miTarea=$task;           
        $this->modalShare=true;
    }
    public function closeShareModal()
    {
        $this->modalShare=false;
    }

    public function sharedTask()
    {
        $task = Task::find($this->miTarea->id);
        $user = User::find($this->user_id);
        $user->sharedTasks()->attach($task->id, ['permission'=>$this->permiso]);
        $this->closeShareModal();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function taskUnshared(Task $task)
    {
        
        $user = User::find(auth()->user()->id);
        $user->sharedTasks()->detach($task->id);
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

}

