<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<!-- Popper JS -->
<script src="/_admin/libs/@popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="/_admin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Defaultmenu JS -->
<script src="/_admin/js/defaultmenu.min.js"></script>

<!-- Node Waves JS-->
<script src="/_admin/libs/node-waves/waves.min.js"></script>

<!-- Sticky JS -->
<script src="/_admin/js/sticky.js"></script>

<!-- Simplebar JS -->
<script src="/_admin/libs/simplebar/simplebar.min.js"></script>
<script src="/_admin/js/simplebar.js"></script>

<!-- Color Picker JS -->
<script src="/_admin/libs/@simonwep/pickr/pickr.es5.min.js"></script>

<!-- Custom JS -->
<script src="/_admin/js/custom.js"></script>

<!-- Custom-Switcher JS -->
<script src="/_admin/js/custom-switcher.min.js"></script>

<!-- Internal Datatables JS -->
<script src="/_admin/js/datatables.js"></script>
<!-- Datatables Cdn -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- extra js-->
<script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.js"></script>

@vite('resources/js/functions.js')

<script>
  $(document).ready(function() {
    window.pageOverlay = $("#page-overlay");

    // basic datatable
    $('.datatable').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
      },
      response: false,
      order: [
        [0, 'desc']
      ],
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, 100, 500, 1000, 5000, -1],
        [10, 25, 50, 100, 500, 1000, 5000, 'All']
      ]
    });

    // .axios-form

    $('.default-form').submit(async function(e) {
      // show page overlay
      pageOverlay.show()
      // submit form
      $(this).submit();
    })

    $('.axios-form').submit(async function(e) {
      e.preventDefault();

      let reload = $(this).data('reload'),
        button = $(this).find('button[type="submit"]'),
        confirm = $(this).data('confirm'),
        callback = $(this).data('callback');

      if (confirm) {
        const confirmResult = await Swal.fire({
          title: 'Are you sure?',
          text: 'You will not be undo this action!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ok',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        })

        if (!confirmResult.isConfirmed) {
          return;
        }
      }

      let form = $(this);
      let url = form.attr('action');
      let method = form.attr('method');
      let data = form.serialize();

      pageOverlay.show()

      axios({
        method: method,
        url: url,
        data: data
      }).then(function(response) {
        if (response.data.status == 200) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.data.message,
          }).then(() => {
            if (reload) {
              setTimeout(() => {
                location.reload();
              }, 1000);
            }
          });


        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.data.message,
          });
        }
      }).catch(function(error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: $catchMessage(error),
        });
      }).finally(function() {
        pageOverlay.hide()
      });
    });

  })
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var dropdownElement = document.getElementById('mainHeaderProfile');
    dropdownElement.addEventListener('click', function() {
      var dropdown = new bootstrap.Dropdown(dropdownElement);
      dropdown.toggle();
    });
  });
</script>
<script>
  $(function() {
    $(".input-upload").change((e) => {
      const element = e.target,
        input_name = element.getAttribute('data-set');
      // upload image
      const formData = new FormData();
      formData.append('file', element.files[0]);

      $.ajax({
        url: '/api/admin/tools/upload?json=1',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          Authorization: 'Bearer ' + userData.access_token
        },
        beforeSend: function() {
          Swal.fire({
            icon: 'warning',
            title: "Đang xử lý!",
            html: "Vui lòng đợi trong giây lát, không tải lại trang",
            showLoading: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          })
        },
        success: function(res) {
          Swal.close();

          element.value = '';

          $(`input[id="${input_name}"]`).val(res.data.link);
        },
        error: function(err) {
          Swal.fire('Thất bại', $catchMessage(err), 'error');
          console.log(err);
        }
      })
    })
  })
</script>
@yield('scripts')
@stack('scripts')
