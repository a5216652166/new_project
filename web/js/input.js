// JavaScript Document
/*
���ߣ�������
���ܣ�ҳ��������
*/


//�ж��Ƿ�Ϊ�ʼ�
function checkMail(mail)
{
	var exp=/(\S)+[@]{1}(\S)+[.]{1}(\w)+/;
    var reg = mail.value.match(exp);
    //var ErrMsg="���������������ڹ�����ڵ��������д��ȷ�������ַ��";   
	var ErrMsg="���������ʽ����";
    if(reg==null)
    {
        alert(ErrMsg);
		mail.value="";
		mail.focus();
		return true;
    }
    else
    {
		return false;
    }
	return false;
}

//�ж��Ƿ�ΪIP
function checkIP(ip)
{/*
	var exp=/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    var reg = ip.value.match(exp);
    //var ErrMsg="��������IP�������ڹ�����ڵ��������д��ȷ��IP��ַ��";  
	var ErrMsg="����ȷ��д����IP��ַ";
    if(reg==null)
    {
        alert(ErrMsg);
		ip.value="";
		ip.focus();
		return true;
    }
    else
    {
		return false;
    }*/
	return false;
}

//�ж��Ƿ�Ϊ������
function checkInt(inputInt)
{
	var newPar=/^[0-9]*[1-9][0-9]*$/;
	if(newPar.test(inputInt.value))
	{
		return false;
	}
	else
	{
		//alert("���������������ڹ�����ڵ��������д��������");
		alert("����д��ȷ�ĸ�ʽ");
		inputInt.value="";
		inputInt.focus();
		return true;
	}
    return false;
}

//�����Ƿ������
function aaa()
{
	alert("22222");
}

//�ж��Ƿ��пո�
function checkSpace(inputSpace)
{
	var checkInputSpace=inputSpace.value;
	if(checkInputSpace.indexOf(' ')>=0)
	{
		//alert("�����������󣬹������λ�õ����벻�ܴ��пո�");
		alert("����д��ȷ�ĸ�ʽ");
		inputSpace.focus();
		return true;
	}
}