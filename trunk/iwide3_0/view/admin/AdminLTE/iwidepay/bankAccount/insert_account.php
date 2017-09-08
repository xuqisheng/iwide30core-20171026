<body>
<?php echo form_open('iwidepay/bankAccount/insert_account',array('id'=>'tosave','enctype'=>'multipart/form-data' ))?>
        <label for="file">上传文件</label>
        <input type="file" name="Filedata" id="file" />

        <input type="submit" name="submit" value="导入" />
    </form>
</body>