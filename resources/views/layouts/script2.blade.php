<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/assets/js/plugins.js') }}"></script>

<!-- apexcharts -->
<script src="{{ URL::asset('build/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- Vector map-->
<script src="{{ URL::asset('build/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ URL::asset('build/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

<!--Swiper slider js-->
<script src="{{ URL::asset('build/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- Dashboard init -->
<script src="{{ URL::asset('build/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ URL::asset('build/assets/js/pages/form-wizard.init.js') }}"></script>



<!-- App js -->
<script src="{{ URL::asset('build/assets/js/app.js') }}"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Buttons Extension CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css"/>

<!-- Buttons Extension JS -->
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

<!-- JSZip for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake for PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="//cdn.ckeditor.com/4.5.11/full/ckeditor.js"></script>

 <!-- Popper.js -->
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

{{-- <script>
$(document).ready(function() {
    $('#professions-table').DataTable({
        // Customize DataTable options here
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
    });
});
</script> --}}


 <script>
    $(document).ready(function() {
        $('#professions-table').DataTable({
            "searching": true,    // Enable search functionality
            "ordering": false,    // Disable column sorting
            "scrollCollapse": true,
            "responsive": true,   // Make the table responsive
            "paging": true,       // Enable pagination
            "pageLength": 10,     // Rows per page
            "lengthMenu": [10, 50, 100, 500, -1], // Page length options
            "dom": 'lBfrtip',     // Layout for DataTable components
            "buttons": [          // Export buttons
                // 'copy',
                // 'csv',
                // 'excel',
                // 'pdf',
                // 'print'
            ]
        });
    });
</script> 





<script>
    $(document).ready(function () {
        CKEDITOR.replace('description');
    });
    </script>

<script>
    $(document).ready(function () {
        CKEDITOR.replace('overview');
    });
    </script>

<script>
    // $('#location_id').on('change', function() {
    //     let locationId = $(this).val();

    //     $('#property_id').empty().append('<option value="">-- Select Property --</option>');

    //     if (locationId) {
    //         $.ajax({
    //             url: `/get-properties/${locationId}`,
    //             type: 'GET',
    //             success: function(data) {
    //                 $.each(data, function(key, property) {
    //                     $('#property_id').append(`<option value="${property.id}">${property.title_two}</option>`);
    //                 });
    //             }
    //         });
    //     }
    // });
</script>
<script>
    let selectedPropertyId = '{{ $property_id ?? '' }}';

    function loadProperties(locationId, selectedId = null) {
        $('#property_id').empty().append('<option value="">-- Select Property --</option>');

        if (locationId) {
            $.ajax({
                url: `/get-properties/${locationId}`,
                type: 'GET',
                success: function(data) {
                    $.each(data, function(key, property) {
                        $('#property_id').append(
                            `<option value="${property.id}" ${property.id == selectedId ? 'selected' : ''}>${property.title_two}</option>`
                        );
                    });
                }
            });
        }
    }

    $(document).ready(function () {
        let currentLocationId = $('#location_id').val();
        if (currentLocationId) {
            loadProperties(currentLocationId, selectedPropertyId);
        }

        $('#location_id').on('change', function () {
            loadProperties($(this).val());
        });
    });
</script>
