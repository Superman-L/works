import time
import pymysql

while True:
    time_now=time.strftime("%H%M",time.localtime())
    print (time_now)
    
    if time_now >= "0000" and time_now <= "0100":
        print('距离运行时间还有：',(2460-int(time_now)))

        #连接数据库
        db = pymysql.connect(host="localhost",user="admin_root", password="root_vote", db="vote_root")
        #定义游标
        cursor = db.cursor()

        try:
            update = "UPDATE tp_user set tp_value=0"
            cursor.execute(update)
            print("数据更新成功")
            db.commit()#提交数据
            continue 

        except:
            print("数据更新失败")
            db.rollback()
            cursor.close()
            db.close()
            continue 

    else :
        time_sleep=60-int(time_now[2:]) #需要休眠多少分钟
        print(time_sleep)
        wanan=time_sleep*60
        time.sleep(wanan)

