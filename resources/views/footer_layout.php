<footer id="footer" class="sticky-footer footer-bg">
    <div class="copyright">
        &copy; AOTECH合創數位科技2020
    </div>
</footer>       
<script>
    //icon提示文字效果
    $(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>

<script>
    $(function () {
        function footerPosition() {
            var contentHeight = document.body.scrollHeight; //網頁正文全文高度
            var winHeight = window.innerHeight; //可視窗口高度，不包括瀏覽器頂部工具欄
            if (!(contentHeight > winHeight)) {
                //當網頁正文高度小於可視窗口高度時，為footer添加類fixed-bottom
                $('#footer').css({ 'position': 'fixed' });
            } else {
                $('#footer').css({ 'position': 'static' });
            }
        }
        footerPosition();
        $(window).resize(footerPosition);
    });
</script>
</body>
</html>