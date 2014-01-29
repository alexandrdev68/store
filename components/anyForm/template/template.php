<form role="form" class="<?=$arParam['class']?>" id="<?=$arParam['id']?>" name="<?=$arParam['name']?>" action="<?=$arParam['action']?>">
  <?foreach($arParam['fields'] as $fType=>$fParams):?>
  <div class="form-group">
    <?if(isset($fParams['label'])):?><?$idLabel = 'id_for_fLabel_'.rand(1, 9999999)?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    <input type="email" class="form-control" id="<?=$idLabel?>" placeholder="Enter email">
  </div>
  <?endif?>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="exampleInputFile">
    <p class="help-block">Example block-level help text here.</p>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox"> Check me out
    </label>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
