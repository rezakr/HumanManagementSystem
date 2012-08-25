<div><?= $param['title'] ?></div>

<form action="<?= $param['action'] ?>" method="post">

<input type="hidden" name="id" value="<?= $param['human']->id ?>"/>
<p>name: <input type="text" name="fname" value="<?= $param['human']->fname ?>"/></p>
<p>last name: <input type="text" name="lname" value="<?= $param['human']->lname ?>"/></p>

<p><Input type = 'Radio' name ='gender' value=1 <?= ($param['human']->gender==true)?'checked':'' ?> />Male
   <Input type = 'Radio' name ='gender' value=0 <?= ($param['human']->gender==false)?'checked':'' ?>/>Female</p>
<p>father ID: <input type="text" name="fatherID" value="<?= $param['human']->fatherID ?>"/></p>
<p>mother ID: <input type="text" name="motherID" value="<?= $param['human']->motherID ?>"/></p>
<p>birthdate: <input type="text" name="birthdate" value="<?= $param['human']->dateOfBirth ?>"/></p>
<p><Input type = 'Radio' name ='dead' value=1 <?= ($param['human']->dead==true)?'checked':'' ?> />Dead
   <Input type = 'Radio' name ='dead' value=0 <?= ($param['human']->dead==false)?'checked':'' ?>/>Alive</p>

<p><input type="submit" /></p>
</form>