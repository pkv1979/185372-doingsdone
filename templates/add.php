<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="add.php" method="post" enctype="multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>

    <input class="form__input <?=isset($errors['name']) ? 'form__input--error' : ''; ?>" type="text" name="name" id="name" value="" placeholder="Введите название">
    <?php if(isset($errors['name'])): ?>
      <p class="form__message">
        <?=$errors['name'];?>
      </p>
    <?php endif;?>
  </div>

  <div class="form__row">
    <label class="form__label" for="project">Проект</label>

    <select class="form__input form__input--select <?=isset($errors['project']) ? 'form__input--error' : ''; ?>" name="project_" id="project">
      <option value="0">Выберите проект</option>
      <?php foreach($projects as $key => $item): ?>
        <option value="<?=$item['id'];?>"><?=$item['name'];?></option>
      <?php endforeach; ?>
    </select>
    <?php if (isset($errors['project'])): ?>
      <p class="form__message">
        <?=$errors['project'];?>
      </p>
    <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="date">Дата выполнения</label>

    <input class="form__input form__input--date <?=isset($errors['date']) ? 'form__input--error' : '';?>" type="date" name="date" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
    <?php if (isset($errors['date'])): ?>
      <p class="form__message">
        <?=$errors['date'];?>
      </p>
    <?php endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="preview">Файл</label>

    <div class="form__input-file">
      <input class="visually-hidden" type="file" name="preview" id="preview" value="">

      <label class="button button--transparent" for="preview">
        <span>Выберите файл</span>
      </label>
    </div>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">
  </div>
</form>