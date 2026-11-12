<script>
    (function() {
        const successMessage = @json(session('success'));
        const errorMessage = @json(session('error'));
        const validationErrors = @json($errors->all());

        function shortSuccessMessage(message) {
            const text = String(message || '').toLowerCase();

            if (/ditambahkan|ditambah/.test(text)) return 'Berhasil ditambahkan.';
            if (/diunggah|diupload|upload/.test(text)) return 'Berhasil diunggah.';
            if (/diperbarui|diperbaharui|diubah|diedit/.test(text)) return 'Berhasil diperbarui.';
            if (/dihapus/.test(text)) return 'Berhasil dihapus.';
            if (/disimpan/.test(text)) return 'Berhasil disimpan.';
            if (/dikirim/.test(text)) return 'Berhasil dikirim.';

            return 'Berhasil.';
        }

        function showFlashAlert() {
            if (typeof window.Swal === 'undefined') {
                return;
            }

            if (successMessage) {
                window.Swal.fire({
                    icon: 'success',
                    title: shortSuccessMessage(successMessage),
                    confirmButtonText: 'OK'
                });
                return;
            }

            const firstValidationError = Array.isArray(validationErrors)
                ? validationErrors[0]
                : null;
            const message = errorMessage || firstValidationError;

            if (message) {
                window.Swal.fire({
                    icon: 'error',
                    title: errorMessage ? 'Gagal' : 'Periksa kembali data',
                    text: message,
                    confirmButtonText: 'OK'
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showFlashAlert);
        } else {
            showFlashAlert();
        }
    })();
</script>
