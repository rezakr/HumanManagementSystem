<form action="/index.php/update/" method="post">

<input type="hidden" name="id" value="<?= $param['id'] ?>"/>
<p>name: <input type="text" name="fname" value="<?= $param['fname'] ?>"/></p>
<p>last name: <input type="text" name="lname" value="<?= $param['lname'] ?>"/></p>
<p>gender: <input type="text" name="gender" value="<?= $param['gender'] ?>" /></p>
<p>father ID: <input type="text" name="fatherID" value="<?= $param['fatherID'] ?>"/></p>
<p>mother ID: <input type="text" name="motherID" value="<?= $param['motherID'] ?>"/></p>
<p>birthdate: <input type="text" name="birthdate" value="<?= $param['birthdate'] ?>"/></p>
<p>dead: <input type="text" name="dead" value="<?= $param['dead'] ?>"/></p>
<p><input type="submit" /></p>
</form>