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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Buttons Extension CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css"/>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

<!-- JSZip for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake for PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- CKEditor — skip on mail edit page --}}
@if(!Request::is('admin/mails/*/edit'))
<script src="//cdn.ckeditor.com/4.5.11/full/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        if (document.getElementById('description')) {
            CKEDITOR.replace('description');
        }
    });
</script>
<script>
    $(document).ready(function () {
        if (document.getElementById('overview')) {
            CKEDITOR.replace('overview');
        }
    });
</script>
@endif

<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<!-- Bootstrap 4 JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#professions-table').DataTable({
            "searching": true,
            "ordering": false,
            "scrollCollapse": true,
            "responsive": true,
            "paging": true,
            "pageLength": 10,
            "lengthMenu": [10, 50, 100, 500, -1],
            "dom": 'lBfrtip',
            "buttons": []
        });
    });
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

<script>
    $("#submitBtn").on("click", function () {
        let totalAmountRaw = 0;
        cart.forEach(item => {
            totalAmountRaw += (item.price * item.qty);
        });

        let discountPercent = parseFloat($("#discountInput").val()) || 0;
        let discountValue   = (totalAmountRaw * discountPercent / 100);
        let afterDiscount   = totalAmountRaw - discountValue;
        let walletUsed      = parseFloat($(".text-warning").text().replace("QAR", "").trim()) || 0;
        let payable         = parseFloat($("#totalAmount").text().trim()) || 0;

        let studentData = {
            _token:           "{{ csrf_token() }}",
            student_name:     $("#studentName").val(),
            cafeteria_id:     $("#cafeteria_id").val(),
            cardNo:           $("#cardNo").val(),
            admissionNo:      $("#admissionNo").val(),
            payment_method:   $("input[name='payment_method']:checked").attr("id") || "cash",
            cart:             cart,
            total_amount:     totalAmountRaw.toFixed(2),
            discount_percent: discountPercent,
            discount_value:   discountValue.toFixed(2),
            after_discount:   afterDiscount.toFixed(2),
            wallet_used:      walletUsed.toFixed(2),
            payable:          payable.toFixed(2)
        };

        if (studentData.payment_method === "credit") {
            studentData.cardDigits = $("#cardDigits").val();
        }

        if (!studentData.student_name) {
            alert("Please select a student first!");
            return;
        }

        $.ajax({
            url: "/admin/pos-order",
            method: "POST",
            data: studentData,
            success: function (response) {
                Swal.fire({
                    title: "✅ Success!",
                    text: response.message + " (Transaction: " + response.transaction_no + ")",
                    icon: "success",
                    timer: 10000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                setTimeout(function () { location.reload(); }, 3000);
            },
            error: function () {
                Swal.fire({
                    title: "❌ Error!",
                    text: "Something went wrong. Please try again.",
                    icon: "error",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        });
    });

    flatpickr("input[name='cafe_from_date']", {
    dateFormat: "d-M-Y"
});

flatpickr("input[name='cafe_to_date']", {
    dateFormat: "d-M-Y"
});

// ----

    flatpickr("input[name='from_date']", {
    dateFormat: "d-M-Y"
    });

    flatpickr("input[name='to_date']", {
        dateFormat: "d-M-Y"
    });


        flatpickr("input[name='cr_date']", {
        dateFormat: "d-M-Y"
    });


          flatpickr("input[name='ss_from']", {
        dateFormat: "d-M-Y"
    });
          flatpickr("input[name='ss_to']", {
        dateFormat: "d-M-Y"
    });
</script>

