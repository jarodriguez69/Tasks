<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use App\Models\State;
use Livewire\Component;
use Livewire\WithPagination;

class TaskComponent extends Component
{
    use WithPagination;
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
    public $search="";
    public $states;
    public $state_id;

    public $open=false;
    public $createModal=false;
    public $taskEdit=[
        'title' => '',
        'description' => '',
        'state_id' => ''
    ];

    public $taskEditId = null;
    

    public function mount()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->users = User::where('id','!=', auth()->user()->id)->get();
        $this->states = State::all();
        $this->user_id = auth()->user()->id;
    }
    
    public function getTasks()
    {
        $user = auth()->user();
        $misTareas = Task::where('user_id', auth()->user()->id)->where('title', 'like', '%'.$this->search.'%')->get();
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

    public function edit(Task $task)
    {
        $this->open = true;
        $this->taskEditId = $task->id;
        $this->taskEdit['title'] = $task->title;
        $this->taskEdit['description'] = $task->description;
        $this->taskEdit['state_id'] = $task->state_id;
    }

    public function update()
    {
       
        $this->validate([
            'taskEdit.title' => 'required',
            'taskEdit.description' => 'required',
            'taskEdit.state_id' => 'required'
        ]);
        
        $task = Task::find($this->taskEditId);
        
        $task->update([
            'title' => $this->taskEdit['title'],
            'description' => $this->taskEdit['description'],
            'state_id' => $this->taskEdit['state_id']
        ]);

        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->reset(['taskEdit', 'taskEditId', 'open']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }
    
    public function create()
    {
        $this->createModal=true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'state_id' => 'required'
        ]);

        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'state_id' => $this->state_id,
            'user_id' => $this->user_id
        ]);

        $task->save();

        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->reset(['title', 'description', 'state_id','createModal']);
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
            $task= Task::create( $this->only('user_id', 'title', 'description', 'state_id'));
        }

        $this->reset(['title', 'description']);
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

