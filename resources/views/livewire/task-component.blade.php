<section wire:poll="renderAllTasks">

<div class="flex justify-end">
  <button class="inline-flex items-center justify-center w-10 h-10 mr-2 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800" wire:click='create'>
    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
      <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" fill-rule="evenodd"></path>
    </svg>
  </button>
</div>

<!-- component -->
<div class="mx-auto">
    
    <div class="w-full flex justify-between items-center mb-3 mt-12 pl-3">
        <div>
            <h3 class="text-lg font-semibold text-slate-800">Tareas</h3>
            <!-- <p class="text-slate-500">Review your selected items.</p> -->
        </div>
        <div class="mx-3">
            <div class="w-full max-w-sm min-w-[200px] relative">
            <div class="relative">
                <input wire:model.live="search" class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="Buscar..."/>
                <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded " type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                </button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="relative flex flex-col w-full h-full overflow-scroll bg-white shadow-md rounded-lg bg-clip-border">
    <table class="w-full text-left table-auto min-w-max">
        <thead>
        <tr class="border-b border-slate-300 bg-slate-50">
            <th class="p-4 text-sm font-normal leading-none text-slate-500">ID</th>
            <th class="p-4 text-sm font-normal leading-none text-slate-500">Nombre</th>
            <th class="p-4 text-sm font-normal leading-none text-slate-500">Descripci&oacute;n</th>
            <th class="p-4 text-sm font-normal leading-none text-slate-500"></th>
        </tr>
        </thead>
        <tbody>

        @foreach($tasks as $task)
          <tr class="hover:bg-slate-50" wire_key="task-{{ $task->id }}">
              <td class="p-4 border-b border-slate-200 py-5">
              <p class="text-sm text-slate-500">{{$task->id}}</p>
              </td>
              <td class="p-4 border-b border-slate-200 py-5">
              <p class="block font-semibold text-sm text-slate-800">{{$task->title}}</p>
              </td>
              <td class="p-4 border-b border-slate-200 py-5">
              <p class="text-sm text-slate-500">{{$task->description}}</p>
              </td>
              <td class="p-4 border-b border-slate-200 py-5">
                @if((isset($task->pivot)))
                  <button wire:click="taskUnshared({{ $task }})" class="bg-blue-800 text white"> Descompartir</button>
                @endif
                @if((isset($task->pivot) && $task->pivot->permission == 'edit') || auth()->user()->id == $task->user_id)
                  
                  <button class="h-8 px-4 m-2 text-sm text-indigo-100 transition-colors duration-150 bg-green-600 rounded-lg focus:shadow-outline hover:bg-green-700" wire:click="edit({{ $task }})">Editar</button>
                  <button class="h-8 px-4 m-2 text-sm text-indigo-100 transition-colors duration-150 bg-yellow-600 rounded-lg focus:shadow-outline hover:bg-yellow-700" wire:click="openShareModal({{ $task->id }})">Compartir</button>
                  <button class="h-8 px-4 m-2 text-sm text-indigo-100 transition-colors duration-150 bg-red-600 rounded-lg focus:shadow-outline hover:bg-red-700" wire:click="destroy({{ $task->id }})">Borrar</button>
                @endif
              
              </td>
          </tr>
        @endforeach

    
        </tbody>
    </table>
    
    </div>
    
    @if ($createModal)
    <!-- Create Task Modal -->
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50">
        <div class="py-12">
          <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900">    
                    <form wire:submit='save'>
                      <div class="mb-4">
                          <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Titulo</label>
                          <x-text-input autofocus wire:model="title" type="text" id="title" name="title"> </x-text-input>
                      </div>
                      <div>
                          <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Descripción</label>
                          <x-text-input wire:model="description" type="text" id="description" name="description"></x-text-input>
                      </div>
                      <div>
                          <label for="state_id" class="block mb-2 text-sm font-medium text-gray-900">Estado</label>
                          <select wire:model="state_id" >
                            <option value="">Seleccione un Estado</option>
                            @foreach($states as $state)
                              <option value="{{$state->id}}">{{$state->name}}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="flex justify-end mt-4">
                        <x-primary-button>Crear</x-primary-button>
                        <x-secondary-button class="ml-2" wire:click="$set('createModal',false)">Cancelar</x-secondary-button>
                      </div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
    </div>
  @endif


    @if ($open)
    <!-- Edit Task Modal -->
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50">
        <div class="py-12">
          <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900">    
                    <form wire:submit='update'>
                      <div class="mb-4">
                          <label for="taskEdit.title" class="block mb-2 text-sm font-medium text-gray-900">Titulo</label>
                          <x-text-input autofocus wire:model="taskEdit.title" type="text" id="taskEdit.title" name="taskEdit.title"></x-text-input>
                      </div>
                      <div>
                          <label for="taskEdit.description" class="block mb-2 text-sm font-medium text-gray-900">Descripción</label>
                          <x-text-input wire:model="taskEdit.description" type="text" id="taskEdit.description" name="taskEdit.description"></x-text-input>
                      </div>
                      <div>
                          <label for="taskEdit.state_id" class="block mb-2 text-sm font-medium text-gray-900">Estado</label>
                          <select wire:model="taskEdit.state_id" >
                            <option value="">Seleccione un Estado</option>
                            @foreach($states as $state)
                              <option value="{{$state->id}}">{{$state->name}}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="flex justify-end mt-4">
                        <x-primary-button>Actualizar</x-primary-button>
                        <x-secondary-button class="ml-2" wire:click="$set('open',false)">Cancelar</x-secondary-button>
                      </div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
    </div>
  @endif




</div>






</section>