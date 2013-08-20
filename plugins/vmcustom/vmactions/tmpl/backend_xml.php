<form>
    <fields name="plugin_param">
		<fields name="<?= $row ?>">
			<fields name="<?= $this->_name ?>">
				<fieldset name="myFieldSet">
					<field type="list" name="compl_product_id" default="<?= $this->params->compl_product_id ?>" label="Товар, определяющий комплект">
                                            <?php foreach($this->params->allProducts as $product){
                                                    print '<option value="'.$product->product_id.'">'.$product->product_name.'</option>';
                                            } ?>
					</field>
                                        <?php $place3Default='';
                                        foreach($this->params->places[2] as $k=>$v)
                                            $place3Default.=$k.','; ?>
					<field name="default_product_id" type="list" default="<?= $this->params->default_product_id ?>" label="Товар на 2 месте по умолчанию">
                                        <?php foreach($this->params->products as $product){
						print '<option value="'.$product->product_id.'">'.$product->product_name.'</option>';
					} ?>
                                        </field>
                                    <field name="place2" type="list" multiple="true" default="<?= $place3Default ?>" label="Товар на 2 месте">
                                        <?php foreach($this->params->products as $product){
						print '<option value="'.$product->product_id.'">'.$product->product_name.'</option>';
					} ?>
                                        </field>
                                        
                    		</fieldset>
			</fields>
        </fields>
    </fields>
</form>