<section wire:poll="renderAllTasks">

<div class="flex justify-end">
  <button class="inline-flex items-center justify-center w-10 h-10 mr-2 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800" wire:click='openCreateModal'>
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
    
    <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
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
          <tr class="hover:bg-slate-50">
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
                  
                  <button class="h-8 px-4 m-2 text-sm text-indigo-100 transition-colors duration-150 bg-green-600 rounded-lg focus:shadow-outline hover:bg-green-700" wire:click="openCreateModal({{ $task }})">Editar</button>
                  <button class="h-8 px-4 m-2 text-sm text-indigo-100 transition-colors duration-150 bg-yellow-600 rounded-lg focus:shadow-outline hover:bg-yellow-700" wire:click="openShareModal({{ $task }})">Compartir</button>
                  <button class="h-8 px-4 m-2 text-sm text-indigo-100 transition-colors duration-150 bg-red-600 rounded-lg focus:shadow-outline hover:bg-red-700" wire:click="deleteTask({{ $task }})">Borrar</button>
                @endif
              
              </td>
          </tr>
        @endforeach

    
        </tbody>
    </table>
    
    </div>
</div>


@if($modal)                
<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

                
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

    
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Crear Nueva Tarea</h3>
              <div class="mt-2">
                <form>
                    <div class="mb-4">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Titulo</label>
                        <input autofocus wire:model="title" type="text" id="title" name="title" class="bg-gray-50 border border-gray-300 text-gray-900">
                    </div>
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Descripción</label>
                        <input wire:model="description" type="text" id="description" name="description" class="bg-gray-50 border border-gray-300 text-gray-900">
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
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto" wire:click="createorUpdateTask">Guardar</button>
          <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" wire:click.prevent="closeCreateModal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@if($modalShare)
<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

                
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

    
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Compartir Tarea</h3>
              <div class="mt-2">
                <form>
                    <div class="mb-4">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Usuario</label>
                        <select wire:model="user_id" name="" id="" class="bg-gray-50 border border-gray-300 text-gray-900">
                          <option value="">Seleccione un usuario</option>
                          @foreach($users as $user)
                          <option value="{{$user->id}}">{{$user->name}}</option>

                          @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Permisos</label>
                        <select wire:model="permiso" name="" id="">
                          <option value="">Seleccione un permiso</option>
                          <option value="edit">Editar</option>
                          <option value="view">Ver</option>
                        </select>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto" wire:click="shareTask">Compartir</button>
          <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" wire:click.prevent="closeShareModal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
</section>