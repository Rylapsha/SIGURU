<script>
    (function(window, document) {
        const defaultLocale = {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            customRangeLabel: 'Rentang Khusus',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
            firstDay: 0
        };

        function buildOptions(options) {
            const pickerOptions = Object.assign({
                showDropdowns: true
            }, options || {});

            pickerOptions.locale = Object.assign(
                {},
                defaultLocale,
                (options && options.locale) || {}
            );

            return pickerOptions;
        }

        window.initSiguruDatePicker = function(element, options) {
            if (
                !window.jQuery ||
                typeof window.jQuery.fn.daterangepicker !== 'function' ||
                typeof window.moment !== 'function'
            ) {
                console.error('Date Range Picker atau Moment.js gagal dimuat.');
                return null;
            }

            const input = window.jQuery(element);

            if (!input.length) {
                return null;
            }

            const existingPicker = input.data('daterangepicker');

            if (existingPicker) {
                return existingPicker;
            }

            const pickerOptions = buildOptions(options);

            if (pickerOptions.singleDatePicker && !pickerOptions.startDate && input.val()) {
                const selectedDate = window.moment(
                    input.val(),
                    pickerOptions.locale.format,
                    true
                );

                if (selectedDate.isValid()) {
                    pickerOptions.startDate = selectedDate;
                }
            }

            input.daterangepicker(pickerOptions);

            return input.data('daterangepicker');
        };

        document.addEventListener('DOMContentLoaded', function() {
            if (!window.jQuery || !window.jQuery('[data-date-picker="single"]').length) {
                return;
            }

            window.jQuery('[data-date-picker="single"]').each(function() {
                window.initSiguruDatePicker(this, {
                    singleDatePicker: true,
                    autoApply: true,
                    locale: {
                        format: 'YYYY-MM-DD',
                        applyLabel: 'Pilih'
                    }
                });
            });
        });
    })(window, document);
</script>
