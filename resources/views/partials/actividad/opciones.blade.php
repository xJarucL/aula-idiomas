<article id="pregunta{{$i}}" class="p-4">
    <div class="mb-3">
        <h3 class="text-[10px] sm:text-xl text-gray-700">
            ¿Cuantas pizzas te comes al día?
        </h3>
        <div class="grid grid-cols-2 mt-3 gap-3 w-full">
            @for ($i = 1; $i < 5; $i++)
                <label
                    class="flex items-center gap-3 p-3 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-teal-700 transition-all peer-checked:border-teal-700 peer-checked:bg-teal-700">
                    <input type="radio" name="respuesta" value="" class="hidden peer" />
                    <span
                        class="flex items-center gap-2 before:content-[''] before:w-4 before:h-4 before:rounded-full before:border-2 before:border-gray-400 peer-checked:before:bg-teal-500 peer-checked:before:border-teal-500"></span>
                    <span class="text-gray-700 font-medium peer-checked:text-teal-600">Opción A</span>
                </label>
            @endfor
        </div>

    </div>
</article>
