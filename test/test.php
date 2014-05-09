<?php
$tone = "C";
$octave = 4;
$freq = 0;
if (isset($_REQUEST['tone'])) {
    require_once '../src/tones.class.php';
    $tones = new Tones();

    $tone = $_REQUEST['tone'];
    if (isset($_REQUEST['octave']))
        $octave = $_REQUEST['octave'];
    $freq = $tones->get_frequency($tone, $octave);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>MidiToHertz</title>
</head>
<body>
	<form method="post">
		<input type="text" name="tone" value="<?=$tone?>" size="3" maxlength="3" /> <input
			type="number" name="octave" value="<?=$octave?>" size="1" maxlength="1" /> <input
			type="submit" value="Get tone" />
	</form>
    <?php
    if (strlen($tone))
        print("\n<p>$tone$octave: $freq Hz</p>");
    ?>
</body>
</html>
