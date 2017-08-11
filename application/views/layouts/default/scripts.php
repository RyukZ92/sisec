<!-- JQuery -->

<script src="<?php echo URLBASE; ?>public/js/jquery-min.js" type="text/javascript"></script>
<script src="<?php echo URLBASE; ?>public/js/jquery-ui.min.js"></script>
<script src="<?php echo URLBASE; ?>public/js/jquery.ui.datepicker-es.js.js"></script>
<script src="<?php echo URLBASE; ?>public/js/jquery.plugin.min.js"></script>
<script src="<?php echo URLBASE; ?>public/js/jquery.timeentry.js"></script>

<!-- Script para el Menú Superior-->
<script src="<?php echo URLBASE; ?>application/views/layouts/default/js/menu-top.js"></script>

<!-- Script para mostrar la Ventana Emergente-->
<script src="<?php echo URLBASE; ?>public/js/ventana-emergente.js"></script>


<!-- Boostrap JS -->
<script src="<?php echo URLBASE; ?>public/librarys/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>

<!-- REMODAL: Script para mostrar la Ventana Emergente responsivas-->
<script src="<?php echo URLBASE; ?>public/librarys/Remodal-master/dist/remodal.min.js"></script>
<script>
  $(document).on('opening', '.remodal', function () {
    console.log('opening');
  });

  $(document).on('opened', '.remodal', function () {
    console.log('opened');
  });

  $(document).on('closing', '.remodal', function (e) {
    console.log('closing' + (e.reason ? ', reason: ' + e.reason : ''));
  });

  $(document).on('closed', '.remodal', function (e) {
    console.log('closed' + (e.reason ? ', reason: ' + e.reason : ''));
  });


  //  The second way to initialize:
  $('[data-remodal-id=modal2]').remodal({
    modifier: 'with-red-theme'
  });
</script>

<!-- Filestyle bootstrap -->
<script src="<?php echo URLBASE; ?>public/librarys/bootstrap-filestyle-1.2.1/js/bootstrap-filestyle.min.js"></script>

<!-- Librería js DE Jquery Validacion Engine Master (validacion fronend de formularios) -->

<script src="<?php echo URLBASE; ?>public/librarys/jQuery-Validation-Engine-master/js/jquery.validationEngine.js"></script>
<script src="<?php echo URLBASE; ?>public/librarys/jQuery-Validation-Engine-master/js/jquery.validationEngine-es.js"></script>

<!-- Scripts Personalizados de la Aplicación-->
<script src="<?php echo URLBASE; ?>public/js/scripts.js"></script>

