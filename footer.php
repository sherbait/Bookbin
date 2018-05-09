<!-- start footer -->

    <footer>
        <div class="container">
            <p class="text-muted text-center">&copy; 2018 A Web Information Systems Project | UP Open University</p>
        </div>
    </footer>


<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script>
    $(document).ready(function(){
        $('.timer').each(function() {
            let target = new Date($(this).data('end')), update, $this = $(this);
            (update = function () {
                let now = new Date();
                $this.text((new Date(target - now)).toUTCString().split(' ')[4]);
                if (Math.floor((target - now)/1000) == 0) return; // timer stops
                setTimeout(update, 1000);
            })();
        });
    })
</script>

</body>
</html>