@extends('components.menu')

@section('title', 'Mensajeria | Aula de Idiomas')

@section('chat')

@php
    $contacto = $contacto ?? null;
    $mensajes = $mensajes ?? collect();
@endphp

<div class="flex flex-row h-full">

    <section class="w-1/5 border-r border-gray-300">
        <div class="flex flex-col">

            <article class="p-2 h-15 border-b border-gray-300">
                <div class="flex justify-between items-center">
                    <h3 class="text-teal-700 text-xl font-semibold">Mensajería</h3>

                    <a href="{{ route('chat.usuarios') }}"
                       class="bg-teal-600 p-2 text-white rounded-lg hover:bg-teal-700 shadow transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z"/>
                            <path fill-rule="evenodd"
                                d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z"/>
                        </svg>
                    </a>
                </div>
            </article>

            <article class="flex flex-col items-center gap-2">
                <div class="overflow-auto h-screen w-full p-2">
                    @foreach($contactos as $c)
                        <a href="{{ route('chat.conversacion', $c->pk_usuario) }}">
                            <div class="flex flex-row items-center w-full gap-2 p-1.5 hover:bg-gray-200 rounded-lg">
                                <img src="{{ $c->img_user ? asset('storage/'.$c->img_user) : asset('img/default.jpg') }}"
                                     class="w-10 h-10 border border-gray-300 rounded-full">

                                <div class="flex flex-col w-52">
                                    <h1 class="text-gray-700 text-[15px] font-semibold truncate">
                                        {{ $c->nombres }} {{ $c->ap_paterno }} {{ $c->ap_materno ?? '' }}
                                    </h1>
                                    <span class="text-gray-500 text-[10px] truncate">Abrir chat...</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </article>

        </div>
    </section>
    <section class="w-4/5">

        @if(!$contacto)
            <div class="flex items-center justify-center h-full text-gray-500 text-xl">
                Selecciona un contacto para comenzar a chatear.
            </div>
        @else

            <article class="p-2 h-15 border-b border-gray-300">
                <div class="flex flex-row items-center gap-2">
                    <img src="{{ $contacto->img_user ? asset('storage/'.$contacto->img_user) : asset('img/default.jpg') }}"
                         class="w-12 h-12 border border-gray-300 rounded-full">

                    <h1 class="text-teal-700 text-xl font-semibold">
                        {{ $contacto->nombres }} {{ $contacto->ap_paterno }} {{ $contacto->ap_materno }}
                    </h1>
                </div>
            </article>

            <article class="bg-gray-200 h-screen">
                <div id="scrollArea" class="overflow-auto h-full flex flex-col-reverse">
                    <div id="contenedorMensajes" class="p-3">
                        @forelse($mensajes as $m)
                            @if($m->de_usuario == Auth::user()->pk_usuario)
                                <div class="mb-4 flex w-full justify-end">
                                    <div class="bg-teal-700 p-3 text-white rounded-xl max-w-6xl">
                                        <p>{{ $m->mensaje }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="mb-4 flex w-full justify-start">
                                    <div class="bg-white p-3 rounded-xl max-w-6xl">
                                        <p>{{ $m->mensaje }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-gray-500">No hay mensajes todavía.</p>
                        @endforelse
                    </div>
                </div>
            </article>
            <div>
                <form id="formMensaje"
                      class="flex flex-row items-center gap-3 p-3 border-t border-gray-300">
                    <input type="hidden" id="receptor" value="{{ $contacto->pk_usuario }}">

                    <input id="mensaje" type="text"
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-teal-600"
                           placeholder="Escribe un mensaje...">

                    <button type="submit"
                            class="bg-teal-700 p-2 text-white rounded-lg hover:bg-teal-800 shadow">
                        Enviar
                    </button>
                </form>
            </div>

        @endif

    </section>

</div>
@endsection


@section('scripts')

@if($contacto)
<script>
    let receptor = "{{ $contacto->pk_usuario }}";
    const yo = "{{ Auth::user()->pk_usuario }}";

    async function actualizarChat() {
        const res = await fetch(`/chat/mensajes/${receptor}`);
        const mensajes = await res.json();

        let html = "";

        mensajes.forEach(m => {
            if (m.de_usuario == yo) {
                html += `
                    <div class="mb-4 flex w-full justify-end">
                        <div class="bg-teal-700 p-3 text-white rounded-xl max-w-6xl">
                            <p>${m.mensaje}</p>
                        </div>
                    </div>`;
            } else {
                html += `
                    <div class="mb-4 flex w-full justify-start">
                        <div class="bg-white p-3 rounded-xl max-w-6xl">
                            <p>${m.mensaje}</p>
                        </div>
                    </div>`;
            }
        });

        document.getElementById("contenedorMensajes").innerHTML = html;

        const area = document.getElementById("scrollArea");
        area.scrollTop = area.scrollHeight;
    }

    setInterval(actualizarChat, 2000);
</script>

<script>
    document.getElementById('formMensaje').addEventListener('submit', async (e) => {
        e.preventDefault();

        let mensaje = document.getElementById('mensaje').value.trim();
        if (mensaje === "") return;

        await fetch("{{ route('chat.enviar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                mensaje: mensaje,
                receptor: receptor
            })
        });

        document.getElementById('mensaje').value = "";
        actualizarChat();
    });
</script>
@endif

@endsection
