<h1>Новое задание</h1>
<?php $this->getWidgets('navbar'); ?>
<hr class="clear-both" />
<form action="new.php" method="POST">
    <div class="form-group">
        <label for="validationCustom01">Ваше имя</label>
        <input id="validationCustom01" type="name" class="form-control <?php if($this->model->errorList['author']) echo 'is-invalid'; ?>" placeholder="Ваше имя" name="author" value="<?=$_POST['author']; ?>" />
        <small id="emailHelp" class="form-text text-muted"></small>
    </div>
    <div class="form-group">
        <label for="validationCustom02">Ваше email</label>
        <input id="validationCustom02" type="email" class="form-control <?php if($this->model->errorList['email']) echo 'is-invalid'; ?>" placeholder="Ваше email" name="email" value="<?=$_POST['email']; ?>" />
        <small id="emailHelp" class="form-text text-muted"></small>
    </div>
    <div class="form-group">
        <label for="validationCustom03">Опишите задание</label>
        <textarea id="validationCustom03" class="form-control <?php if($this->model->errorList['task']) echo 'is-invalid'; ?>" rows="3" placeholder="Опишите задание" name="task"><?=$_POST['task']; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary mb-2">Создать</button>
</form>