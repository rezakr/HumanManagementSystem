<div><?= $param['title'] ?></div>

<form action="<?= $param['action'] ?>" method="post">

<input type="hidden" name="id"/>
<p>boxname: <input type="text" name="boxname" /></p>
<p>weight: <input type="text" name="weight"/></p>
<p>moved: <input type="text" name="moved"/></p>

<p><input type="submit" /></p>
</form>