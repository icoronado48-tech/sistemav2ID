   {{-- Este archivo parcial se usa para renderizar una fila de ingrediente. --}}
   <div class="row ingrediente-row border border-info rounded p-2 mb-2">
       <div class="form-group col-md-5">
           <label for="ingredientes_{{ $index }}_materia_prima_id">Materia Prima <span
                   class="text-danger">*</span></label>
           <select class="form-control @error("ingredientes.${index}.materia_prima_id") is-invalid @enderror"
               id="ingredientes_{{ $index }}_materia_prima_id"
               name="ingredientes[{{ $index }}][materia_prima_id]" required>
               <option value="">Seleccione una materia prima</option>
               @foreach ($materiasPrimas as $materiaPrima)
                   <option value="{{ $materiaPrima->id }}"
                       {{ isset($selectedMateriaPrimaId) && $selectedMateriaPrimaId == $materiaPrima->id ? 'selected' : '' }}>
                       {{ $materiaPrima->nombre_materia_prima }}
                   </option>
               @endforeach
           </select>
           @error("ingredientes.${index}.materia_prima_id")
               <span class="invalid-feedback d-block" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
           @enderror
       </div>
       <div class="form-group col-md-5">
           <label for="ingredientes_{{ $index }}_cantidad_necesaria">Cantidad Necesaria <span
                   class="text-danger">*</span></label>
           <input type="number"
               class="form-control @error("ingredientes.${index}.cantidad_necesaria") is-invalid @enderror"
               id="ingredientes_{{ $index }}_cantidad_necesaria"
               name="ingredientes[{{ $index }}][cantidad_necesaria]" min="0.01" step="0.01"
               value="{{ old("ingredientes.${index}.cantidad_necesaria", $cantidadNecesaria) }}" required>
           @error("ingredientes.${index}.cantidad_necesaria")
               <span class="invalid-feedback d-block" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
           @enderror
       </div>
       <div class="col-md-2 d-flex align-items-end mb-3">
           <button type="button" class="btn btn-danger btn-sm remove-ingrediente-btn"><i
                   class="fas fa-trash"></i></button>
       </div>
   </div>
