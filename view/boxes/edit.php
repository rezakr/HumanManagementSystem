<div><?= $param['title'] ?></div>

<form action="<?= $param['action'] ?>" method="post">

<input type="hidden" name="id" value="<?= $param['result']->id ?>"/>
<p>boxname: <input type="text" name="boxname" value="<?= $param['result']->boxname ?>"/></p>
<p>weight: <input type="text" name="weight" value="<?= $param['result']->weight ?>"/></p>
<p>moved: <input type="text" name="moved" value="<?= $param['result']->moved ?>"/></p>

<p><input type="submit" /></p>
</form>