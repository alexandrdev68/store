<?print_r($arPar)?>
<form role="form" class="<?=$arPar['class']?>" method="<?=$arPar['method']?>" id="<?=$arPar['id']?>" name="<?=$arPar['name']?>" action="<?=$arPar['action']?>">
  <?foreach($arPar['fields'] as $fType=>$fParams){?>
  <div class="<?=$arFieldType[$fType]?>">
    <?$idLabel = 'id_for_fLabel_'.rand(1, 9999999)?>
  	<?if($fType == 'text' || $fType == 'hidden' || $fType == 'file'){?>
  	<?if(isset($fParams['label'])):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    <input type="<?=$fType?>" class="form-control" id="<?=$idLabel?>" placeholder="<?=$fParams['label']?>" name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>" value="<?=(isset($fParams['value']) ? $fParams['value'] : '')?>">
    <?}elseif($fType == 'checkbox'){?>
    	<label>
    		<input type="<?=$fType?>" name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>"><?=$fParams['label']?>
    	</label>
    <?}elseif($fType == 'textarea'){?>
    	<?if(isset($fParams['label'])):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    	<textarea id="<?=$idLabel?>" cols="<?=(isset($fParams['cols']) ? $fParams['cols'] : '1')?>" rows="<?=(isset($fParams['rows']) ? $fParams['rows'] : '1')?>" name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>">
    		<?=(isset($fParams['value']) ? $fParams['value'] : '')?>
    	</textarea>
    <?}elseif($fType == 'select'){?>
    	<?if(isset($fParams['label'])):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    	<select class="<?=$arFieldType[$fType]?>" id="<?=$idLabel?>" <?if(isset($fParams['multiple']) && $fParams['multiple'] == '1'):?>multiple<?endif?> name="<?=(isset($fParam['name']) ? $fParams['name'] : '')?>">
    		<?if(isset($fParams['option']) && count($fParams['option']) > 0){?>
    			<?foreach($fParams['option'] as $arOption):?>
    			<option<?if(isset($arOption['value'])):?> value="<?=$arOption['value']?>"<?endif?>><?if(isset($arOption['text'])):?><?=$arOption['text']?><?endif?></option>
    			<?endforeach?>
    		<?}?>
    	</select>
    <?}?>
  </div>
  <?}?>
  <?foreach($arPar['buttons'] as $bType=>$bParams){?>
  <?if($bType == 'submit'):?><input type="<?=$bType?>" name="<?=(isset($bParams['name']) ? $bParams['name'] : '')?>" value="<?=$bParams['value']?>"<?if(isset($bParams['class'])):?>class="<?=$bParams['class']?>"<?endif?><?if(isset($bParams['id'])):?>id="<?=$bParams['id']?>"<?endif?>>
  <?elseif($bType == 'button'):?><button type="submit" name="<?=(isset($bParams['name']) ? $bParams['name'] : '')?>" <?if(isset($bParams['class'])):?>class="<?=$bParams['class']?>"<?endif?><?if(isset($bParams['id'])):?>id="<?=$bParams['id']?>"<?endif?>><?=$bParams['value']?></button>
  <?endif?>
  <?}?>
</form>
