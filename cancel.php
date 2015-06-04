<script>
    alert("Payment Cancelled");
    window.onload = function () {
        if (window.opener) {
            window.close();
        }
        else {
            if (top.dg.isOpen() == true) {
                top.dg.closeFlow();
                return true;
            }
        }
    };
</script>
