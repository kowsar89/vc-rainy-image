<?php
$id  = uniqid( 'rivc_', true );
?>
<div class="rainyimg">
    <img id="<?php echo $id;?>" alt="<?php echo $alt;?>" src="<?php echo $src;?>"  />
</div>
<script>
    document.getElementById("<?php echo $id;?>").onload = function() {
        var engine = new RainyDay({
            image: this,
            parentElement: this.parentElement,
            blur: <?php echo $blur;?>,
            opacity: <?php echo $opacity;?>});
        engine.rain([ [1, 2, <?php echo $initial;?>] ]);
        engine.rain([ [3, 3, 0.88], [5, 5, 0.9], [6, 2, 1] ], <?php echo $interval;?>);
    };
</script>