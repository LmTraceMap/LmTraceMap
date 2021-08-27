import mysql.connector
import sys

local = []

#python3 insertInfo2db.py SraBioSampleInfo_sel.txt的路徑
#將SraBioSampleInfo_sel.txt加入資料庫
#清空資料表SQL: delete from SraBioSampleInfo

print(sys.argv[1])
local_gene = open(sys.argv[1], mode='r')

for line in local_gene.readlines():
    line = line.strip('\n')  # 把換行字元刪掉

    if '\"' in line:    #把,字元暫時換成&字元 因為要用,切開 會切錯
        first_location=line.find('\"',0)
        second_location=line.find('\"',first_location+1)

        while first_location!=-1 and second_location!=-1:
            for i in range(first_location,second_location):
                if line[i]==',':
                    line = line[:i] + '&' + line[i+1:]
            first_location=line.find('\"',second_location+1)
            second_location=line.find('\"',first_location+1)

    s = line.split(',')  # 用,切割
    local.append(s)

# connect database
connection = mysql.connector.connect(
    host='120.126.17.217',
    port=3306,
    database='L. monocytogenes global tracking web tool',
    user='bubuuuu',
    password='00000000',
)

if connection.is_connected():
    cursor = connection.cursor()

    for i in range(1, len(local)):   #把資料加進去
        for j in range(0,len(local[0])):
            local[i][j] = local[i][j].strip('\"')
            local[i][j] = local[i][j].replace('&', ',')  # 把&換成,
            local[i][j] = local[i][j].replace('\'','\\\'')    #把'換成\'
            if j==0:
                temp="'"+local[i][j]+"'"
            else:
                temp=temp+"'"+local[i][j]+"'"
            if j!=len(local[0])-1:
                temp=temp+","

        #print(temp)
        cursor.execute("INSERT INTO `SraBioSampleInfo` VALUES (%s);" %(temp))
        connection.commit()
    
    # 關資料庫
    cursor.close()
    connection.close()
    print("資料庫連線已關閉")

else:
    print("資料庫連接失敗")