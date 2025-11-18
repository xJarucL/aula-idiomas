<article class="mt-4">
    <div class="flex flex-col gap-2 pr-2 overflow-auto h-[38rem] sm:h-[35rem]">
        @foreach($usuarios as $usuario)
            <a href="{{route('chat.conversacion', $usuario->pk_usuario)}}">
                <div class="flex flex-row items-center w-full gap-2 p-1.5 border border-gray-300 hover:bg-gray-100 rounded-lg">
                    <img src="{{ $usuario->img_user ? asset('storage/'.$usuario->img_user) : asset('img/default.jpg') }}" alt="" class="w-13 h-13 border border-gray-300 rounded-full">
                    <div class="flex flex-col w-full">
                        <h1 class="text-gray-700 text-[15px] font-semibold">
                            {{$usuario->nombres}} {{$usuario->ap_paterno}} {{$usuario->ap_materno ?? ''}}
                        </h1>
                        <span>
                            @if($usuario->fk_tipo_usuario == 1)
                                Alumno
                            @elseif($usuario->fk_tipo_usuario == 2)
                                Docente
                            @elseif($usuario->fk_tipo_usuario == 3)
                                Coordinador
                            @else
                                Desconocido
                            @endif
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</article>
