<?php
	//Simple script used to send logs from a WP site to an Azure storage container
	//Script uses helper class from WP plugin "Microsoft Azure Storage", Official SDK can be used as well
	//If running from functions.php, will need to include WP's file.php
	//If minimalism is needed, require the classes Windows_Azure_Helper & rest_api_client from the plugin

			$filePath = $ordersProcessedPath = $orderExportPath = WP_CONTENT_DIR;

			$orderExportPath .= "/my/log/dir";
			$ordersProcessedPath .= "/my/log/dir/processed/";
			//Used if your generated logs have a unique prefix
			//Change to "" if they don't
			$splitKey = "azure-test-orders-export-";

			$fileList = scandir($orderExportPath);

			foreach($fileList as $file) {

				//Skips directories
				if(is_file($orderExportPath."/".$file)){
					//Remove XMl IDs
					$fileTimestamp = preg_split("/(.$splitKey.)/",$file)[1];
					//Ignore non-orders files
					if (isset($fileTimestamp)){
						//create the new file name to send
						$newUploadFile = $splitKey.$fileTimestamp;
						echo $newUploadFile."<br />";

						$azureObj = new Windows_Azure_Helper();
						$containerName = $azureObj->get_default_container();
						$accountName = $azureObj->get_account_name();
						$accountKey = $azureObj->get_account_key();

						if(is_wp_error($azureObj->put_uploaded_file_to_blob_storage($containerName,$newUploadFile,$orderExportPath."/".$file,$accountName,$accountKey))){
							echo "Failed to upload file <br />";
						}
						else{
							//File uploaded to Azure
							echo "Uploaded File successfully";
							echo $newUploadFile. "<br />";
							//Move file to proccessed folder
							if (rename($orderExportPath."/".$file,$ordersProcessedPath."/".$newUploadFile)){
								echo "<br />"."Moved file: ". $file ."<br />";
							}
							else{
								echo "<br />"."Failed to move file: ". $file ."<br />";
							}
						}
						//Delete azure instance
						unset($azureObj);
					}
				}
			}
			?>
