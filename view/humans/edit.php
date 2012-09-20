<div><?= $param['title'] ?></div>

<form action="<?= $param['action'] ?>" method="post">

<input type="hidden" name="id" value="<?= $param['result']->id ?>"/>
<p>name: <input type="text" name="fname" value="<?= $param['result']->fname ?>"/></p>
<p>last name: <input type="text" name="lname" value="<?= $param['result']->lname ?>"/></p>

<p><Input type = 'Radio' name ='gender' value=1 <?= ($param['result']->gender==true)?'checked':'' ?> />Male
   <Input type = 'Radio' name ='gender' value=0 <?= ($param['result']->gender==false)?'checked':'' ?>/>Female</p>
<p>father ID: <input type="text" name="fatherID" value="<?= $param['result']->fatherID ?>"/></p>
<p>mother ID: <input type="text" name="motherID" value="<?= $param['result']->motherID ?>"/></p>
<p>birthdate: <input type="text" name="birthdate" value="<?= $param['result']->dateOfBirth ?>"/></p>
<p><Input type = 'Radio' name ='dead' value=1 <?= ($param['result']->dead==true)?'checked':'' ?> />Dead
   <Input type = 'Radio' name ='dead' value=0 <?= ($param['result']->dead==false)?'checked':'' ?>/>Alive</p>

<p><input type="submit" /></p>
</form>