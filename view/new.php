<div><?= $param['title'] ?></div>

<form action="<?= $param['action'] ?>" method="post">

<input type="hidden" name="id"/>
<p>name: <input type="text" name="fname" /></p>
<p>last name: <input type="text" name="lname"/></p>

<p><Input type = 'Radio' name ='gender' value=1  />Male
   <Input type = 'Radio' name ='gender' value=0 />Female</p>
<p>father ID: <input type="text" name="fatherID" /></p>
<p>mother ID: <input type="text" name="motherID" /></p>
<p>birthdate: <input type="text" name="birthdate" "/></p>
<p><Input type = 'Radio' name ='dead' value=1  />Dead
   <Input type = 'Radio' name ='dead' value=0 />Alive</p>

<p><input type="submit" /></p>
</form>