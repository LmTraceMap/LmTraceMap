import time
import json
import sys

start = time.time()

local_gene = open('./allele_profile_20683.txt', mode='r')  # 要拿來比較的基因序
#user_gene = open('./3.wgProfiles/allele_profile.txt', mode='r')  # 使用者上傳的column
user_gene = open('../file/'+sys.argv[1]+'/output/allele_profile.txt', mode='r')  # 使用者上傳的column

user = []
local = []
distances = {}
distance=[]

for line in user_gene.readlines():
    line = line.strip('\n')  # 把換行字元刪掉
    s = line.split('\t')  # 用tab切割
    user.append(s)

for line in local_gene.readlines():
    line = line.strip('\n')  # 把換行字元刪掉
    s = line.split('\t')  # 用tab切割
    local.append(s)

for i in range(1, len(local[0])):
    count = 0
    for j in range(1, len(local)):
        if local[j][i] != user[j][1]:  # 如果序列數值不一樣,就加1
            count = count+1
    distances[local[0][i].strip(' ')] = count

# 把dict轉換成list進行sort
distance = sorted(distances.items(), key=lambda x: x[1], reverse=False)

i = 0
for element in distance:  # 印出前50   #寫入html
    i = i+1
    if i <= 50:
        distance[i-1] = list(distance[i-1])
        #distance[i-1].append(i)  # rank排名
        print( str(distance[i-1][0]) +" "+ str(distance[i-1][1]))    #[菌的名字,距離]
    else:
        break

local_gene.close()
user_gene.close()
