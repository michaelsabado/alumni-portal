<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="https://unpkg.com/@papyrs/stylo@latest/dist/stylo/stylo.esm.js"></script>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    $("img").click(function() {
        $("#full-image").attr("src", $(this).attr("src"));
        $('#image-viewer').show();
    });

    $("#image-viewer .close").click(function() {
        $('#image-viewer').hide();
    });

    var pass_preview = false;
    $('.preview-pass').click(function() {
        if (!pass_preview) {
            pass_preview = true;
            $(".pass-prev").attr('type', 'text');
            $(this).addClass('fa-eye');
            $(this).removeClass('fa-eye-slash');
        } else {
            pass_preview = false;
            $(".pass-prev").attr('type', 'password');
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
        }
    })
</script>