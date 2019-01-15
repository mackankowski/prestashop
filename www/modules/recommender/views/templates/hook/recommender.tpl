{function exec}
	{if $recommender_type == 'most-popular'}
		{$url="http://172.20.83.76:8080/`$recommender_type`"}
	{else}
		{$url="http://172.20.83.76:8080/`$recommender_type`/`$uid`"}
	{/if}
	<div class="recommender-container-{$recommender_type} row" style="display: none; margin-bottom: 3em;">
		<hr/>
		{if $recommender_type == 'most-popular'}
				<h1 style="line-height: 2em;">Najpopularniejsze produkty</h1>
		{/if}
		{if $recommender_type == 'user-based'}
				<h1 style="line-height: 2em;">Rekomendacje (oparte na użytkowniku)</h1>
		{/if}
		{if $recommender_type == 'item-based'}
				<h1 style="line-height: 2em;">Rekomendacje (oparte na produktach) </h1>
		{/if}
		<div class="result-for-{$recommender_type}"></div>
	</div>
	<script>
	var products = {$products|json_encode}; 
	var recommender_type = {$recommender_type|json_encode}
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.querySelector(".recommender-container-{$recommender_type}").style.display="inline-block";
				response=xhttp.responseText;
				var data;
				// <!-- extracting response string from array []:
				let newJson = response.replace(/([a-zA-Z0-9]+?):/g, '"$1":');
				newJson = newJson.replace(/'/g, '"');
				// -->
				data = JSON.parse(newJson);
				for (var i = 0; i < data.length; i++) {
					if (i == 6) break;
					var index = (recommender_type == 'most-popular') ? data[i] - 1 : data[i].id - 1;
					document.querySelector(".result-for-{$recommender_type}").innerHTML += `
					<li class="col-xs-12 col-sm-4 col-md-2" style="list-style: none;">
						<div class="product-container" itemscope itemtype="https://schema.org/Product" style="text-align: center;">
							<a class="product_img_link" href="index.php?controller=product&id_product=`+(index+1)+`" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
								<div class="product-image-container" style="border: 1px solid rgba(0,0,0,0.1);">
										<img class="replace-2x img-responsive" src="`+products[index].id_product+`-large_default/`+products[index].link_rewrite+`.jpg" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
								</div>
								<h5 class="product-name" itemprop="name">`+products[index].name+`</h5>
								<br/>
								<span class="price product-price">`+products[index].price.substring(0, products[index].price.length - 4)+` zł<h4>
							</a>
						</div>
					</li>
					`
				}
			}
	};
	xhttp.open("GET", "{$url}", false);
	xhttp.send();
	</script>
{/function}

{if $page_name != 'product'}
	{if $recommender_type == 'most-popular'}
		{exec}
	{else}
		{if isset($uid) && $uid}
			{exec}
		{/if}
	{/if}
{/if}




