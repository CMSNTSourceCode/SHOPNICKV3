<!-- latest jquery-->
<script src="/_admin/js/jquery-3.6.0.min.js"></script>
<!-- Bootstrap js-->
<script src="/_admin/js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- feather icon js-->
<script src="/_admin/js/icons/feather-icon/feather.min.js"></script>
<script src="/_admin/js/icons/feather-icon/feather-icon.js"></script>
<!-- scrollbar js-->
<script src="/_admin/js/scrollbar/simplebar.js"></script>
<script src="/_admin/js/scrollbar/custom.js"></script>
<!-- Sidebar jquery-->
<script src="/_admin/js/config.js"></script>
<script src="/_admin/js/sidebar-menu1.js"></script>
<script src="/_admin/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.20/sweetalert2.min.js"></script>
<script src="/_admin/js/prism/prism.min.js"></script>
<script src="/_admin/js/clipboard/clipboard.min.js"></script>
<script src="/_admin/js/custom-card/custom-card.js"></script>
<script src="/_admin/js/typeahead/handlebars.js"></script>
{{-- <script src="/_admin/js/typeahead/typeahead.bundle.js"></script>
<script src="/_admin/js/typeahead/typeahead.custom.js"></script> --}}
{{-- <script src="/_admin/js/typeahead-search/handlebars.js"></script> --}}
{{-- <script src="/_admin/js/typeahead-search/typeahead-custom.js"></script> --}}
<!-- Template js-->
<script src="/_admin/js/script.js"></script>
{{-- <script src="/_admin/js/theme-customizer/customizer.js"></script> --}}
<!-- extra js-->
<script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

@vite('resources/js/functions.js')
@yield('scripts')
@stack('scripts')

<script>
  $(document).ready(function() {
    $(".datatable").DataTable({
      order: [0, 'desc'],
      responsive: false,
    })
  })

  $(document).ready(function() {
    const setActiveMenu = () => {
      const currentUrl = window.location.href;
      const menuItem = document.querySelectorAll('.sidebar-links li a');
      menuItem.forEach((item) => {
        if (item.href === currentUrl) {
          item.classList.add('active');
        }
      });

    }
    setActiveMenu()
  })
</script>
