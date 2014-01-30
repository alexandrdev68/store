<form role="form" class="<?=$arParam['class']?>" id="<?=$arParam['id']?>" name="<?=$arParam['name']?>" action="<?=$arParam['action']?>">
  <?foreach($arParam['fields'] as $fType=>$fParams):?>
  <div class="<?=$arFieldType['$fType']?>">
    <?$idLabel = 'id_for_fLabel_'.rand(1, 9999999)?>
  	<?if(isset($fParams['label']) && ($fType == 'text' || $fType == 'hidden')):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label>
    <input type="<?=$fType?>" class="form-control" id="<?=$idLabel?>" placeholder="<?=$fParams['label']?>" name="<?=(isset($fParam['name']) ? $fParams['name'] : '')?>">
    <?elseif($fType == 'checkbox'):?>
    	<label>
    		<input type="<?=$fType?>" name="<?=(isset($fParam['name']) ? $fParams['name'] : '')?>"><?=$fParams['label']?>"
    	</label>
    <?elseif($fType == 'textarea'):?>
    	<?if(isset($fParams['label']) && ($fType == 'text' || $fType == 'hidden')):?><?$idLabel = 'id_for_fLabel_'.rand(1, 9999999)?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    	<textarea id="<?=$idLabel?>" cols="<?=(isset($fParam['cols']) ? $fParams['cols'] : '1')?>" rows="<?=(isset($fParam['rows']) ? $fParams['rows'] : '1')?>" name="<?=(isset($fParam['name']) ? $fParams['name'] : '')?>">
    		<?=(isset($fParam['value']) ? $fParams['value'] : '')?>
    	</textarea>
    <?elseif($fType == 'select'):?>
    	<select <?if(isset($fParam['multiple']) && $fParam['multiple'] == '1'):?>multiple<?endif?> name="<?=(isset($fParam['name']) ? $fParams['name'] : '')?>">
    		
    	</select>
    <?endif?>
  </div>
  <?endif?>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
