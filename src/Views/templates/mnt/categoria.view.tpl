<h1>{{modedsc}}</h1>
<section class="row">
  <form action="index.php?page=Mnt_Categoria&mode={{mode}}&catid={{catid}}"
    method="POST"
    class="col-6 col-3-offset"
  >
    <section class="row">
    <label for="catid" class="col-4">Código</label>
    <input type="hidden" id="catid" name="catid" value="{{catid}}"/>
    <input type="hidden" id="mode" name="mode" value="{{mode}}"/>

    <input type="text" readonly name="catiddummy" value="{{catid}}"/>
    </section>
    <section class="row">
      <label for="catnom" class="col-4">Categoría</label>
      <input type="text" {{readonly}} name="catnom" value="{{catnom}}" maxlength="45" placeholder="Nombre de Categoría"/>
      {{if catnom_error}}
        <span class="error col-12">{{catnom_error}}</span>
      {{endif catnom_error}}
    </section>
    <section class="row">
      <label for="catest" class="col-4">Estado</label>
      <select id="catest" name="catest" {{if readonly}}disabled{{endif readonly}}>
        <option value="ACT" {{catest_ACT}}>Activo</option>
        <option value="INA" {{catest_INA}}>Inactivo</option>
      </select>
    </section>
    {{if has_errors}}
        <section>
          <ul>
            {{foreach general_errors}}
                <li>{{this}}</li>
            {{endfor general_errors}}
          </ul>
        </section>
    {{endif has_errors}}
    <section>
      {{if show_action}}
      <button type="submit" name="btnGuardar" value="G">Guardar</button>
      {{endif show_action}}
      <button type="button" id="btnCancelar">Cancelar</button>
    </section>
  </form>
</section>


<script>
  document.addEventListener("DOMContentLoaded", function(){
      document.getElementById("btnCancelar").addEventListener("click", function(e){
        e.preventDefault();
        e.stopPropagation();
        window.location.assign("index.php?page=Mnt_Categorias");
      });
  });
</script>
