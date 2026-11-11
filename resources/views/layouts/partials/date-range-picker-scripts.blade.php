<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.jQuery || typeof window.jQuery.fn.daterangepicker !== 'function') {
            console.error('Date Range Picker gagal dimuat.');
            return;
        }

        window.jQuery('[data-date-picker="single"]').each(function () {
            const input = window.jQuery(this);

            if (input.data('daterangepicker')) {
                return;
            }

            input.daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Pilih',
                    cancelLabel: 'Batal',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: [
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ],
                    firstDay: 1
                }
            });
        });
    });
</script>
