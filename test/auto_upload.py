import time
from selenium import webdriver
from selenium.webdriver.support.ui import Select
driver = webdriver.Chrome()
driver.get('http://120.126.17.217/hua_map/test/upload.php')
driver.find_element_by_id('upload_file').send_keys('/var/www/html/hua_map/test/test.fa')

time.sleep(3)

driver.switch_to_alert().accept()
time.sleep(3)

driver.find_element_by_id('mail').send_keys('sharonxie530@gmail.com')
time.sleep(3)

select = Select(driver.find_element_by_name('countries'))
select.select_by_visible_text("Taiwan")

driver.find_element_by_name('upload_form').submit()
#driver.find_element_by_id('upload_img_button').click
time.sleep(5)
