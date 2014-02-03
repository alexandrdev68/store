<form role="form" class="<?=$arPar['class']?>" method="<?=$arPar['method']?>" id="<?=$arPar['id']?>" name="<?=$arPar['name']?>" action="<?=$arPar['action']?>">
  <?foreach($arPar['fields'] as $index=>$fParams){?>
  <?if($fParams == 'divider'):?><div class="clear"></div><?continue?><?endif?>
  <div class="<?=$arFieldType[$fParams['type']]?>">
    <?$idLabel = 'id_for_fLabel_'.rand(1, 9999999)?>
  	<?if($fParams['type'] == 'text' || $fParams['type'] == 'hidden' || $fParams['type'] == 'file' || $fParams['type'] == 'email' || $fParams['type'] == 'password'){?>
  	<?if(isset($fParams['label'])):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    <input type="<?=$fParams['type']?>" class="<?=($fParams['type'] == 'file' ? '' : 'form-control')?>" id="<?=$idLabel?>" placeholder="<?=$fParams['label']?>" name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>" value="<?=(isset($fParams['value']) ? $fParams['value'] : '')?>" <?if(isset($fParams['validate'])):?>data-validate="<?=$fParams['validate']?>"<?endif?>>
    <?}elseif($fParams['type'] == 'checkbox' || $fParams['type'] == 'radio'){?>
    	<label class="checkbox-inline">
    		<input type="<?=$fParams['type']?>" name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>"><?=$fParams['label']?>
    	</label>
    <?}elseif($fParams['type'] == 'textarea'){?>
    	<?if(isset($fParams['label'])):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    	<textarea id="<?=$idLabel?>" cols="<?=(isset($fParams['cols']) ? $fParams['cols'] : '1')?>" rows="<?=(isset($fParams['rows']) ? $fParams['rows'] : '1')?>" name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>" <?if(isset($fParams['validate'])):?>data-validate="<?=$fParams['validate']?>"<?endif?>><?=(isset($fParams['value']) ? $fParams['value'] : '')?></textarea>
    <?}elseif($fParams['type'] == 'select'){?>
    	<?if(isset($fParams['label'])):?><label for="<?=$idLabel?>"><?=$fParams['label']?></label><?endif?>
    	<select class="<?=$arFieldType[$fParams['type']]?>" id="<?=$idLabel?>" <?if(isset($fParams['multiple']) && $fParams['multiple'] == '1'):?>multiple<?endif?> name="<?=(isset($fParams['name']) ? $fParams['name'] : '')?>">
    		<?if(isset($fParams['option']) && count($fParams['option']) > 0){?>
    			<?foreach($fParams['option'] as $arOption):?>
    			<option<?if(isset($arOption['value'])):?> value="<?=$arOption['value']?>"<?endif?>><?if(isset($arOption['text'])):?><?=$arOption['text']?><?endif?></option>
    			<?endforeach?>
    		<?}?>
    	</select>
    <?}?>
  </div>
  <?}?>
  <div class="clear"></div>
  <div class="controls text-center">
  <?foreach($arPar['buttons'] as $bType=>$bParams){?>
  <?if($bParams['type'] == 'submit'):?><input type="<?=$bParams['type']?>" name="<?=(isset($bParams['name']) ? $bParams['name'] : '')?>" value="<?=$bParams['value']?>"<?if(isset($bParams['class'])):?>class="<?=$bParams['class']?> <?=(isset($bParams['role']) ? '_button_'.$bParams['role'] : '')?>"<?endif?><?if(isset($bParams['id'])):?>id="<?=$bParams['id']?>"<?endif?>>
  <?elseif($bParams['type'] == 'button'):?><button type="submit" name="<?=(isset($bParams['name']) ? $bParams['name'] : '')?>" <?if(isset($bParams['class'])):?>class="<?=$bParams['class']?> <?=(isset($bParams['role']) ? '_button_'.$bParams['role'] : '')?>"<?endif?><?if(isset($bParams['id'])):?>id="<?=$bParams['id']?>"<?endif?>><?=$bParams['value']?></button>
  <?endif?>
  <?}?>
  </div>
</form>
