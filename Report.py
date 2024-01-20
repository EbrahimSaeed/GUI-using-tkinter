import streamlit as st
import time
import streamlit_javascript
from selenium import webdriver
import subprocess
import plotly.express as px
import matplotlib.pyplot as plt
import os
import warnings
warnings.simplefilter(action='ignore', category=UserWarning)
import pandas as pd
import mysql.connector
from datetime import datetime
from streamlit_extras.add_vertical_space import add_vertical_space
conn = mysql.connector.connect(host="sama-scada.com", user="samascad_me", password="uDVX!7tJsSmL", database="samascad_Fuel_Monitoring_System")
cursor = conn.cursor()
query = "select * from Sensor"
cursor.execute(query)
cursor.fetchall()
conn.commit()

df = pd.read_sql(query, con = conn)


st.set_page_config(page_title="Water Monitoring System", page_icon=":bar_chart:", layout="wide")
st.markdown("<h1 style='text-align: center; color: white;'> عرض وتحميل التقارير   </h1>", unsafe_allow_html=True)

cl1, cl2 = st.columns(2)
df["posted_date"] = pd.to_datetime(df["reading_time"]).dt.date
startDate = pd.to_datetime(df["posted_date"]).min()
endDate = pd.to_datetime(df["posted_date"]).max()


with cl1:
    date1 = pd.to_datetime(st.date_input("من تـريخ", startDate))
    time1 = st.time_input("من زمن")
    start_datetime = datetime.combine(date1, time1 )
    df["start_datetime"] = start_datetime


with cl2:
    date2 = pd.to_datetime(st.date_input("الى تـريخ",endDate))
    time2 = st.time_input("الى زمن")
    end_datetime = datetime.combine(date2, time2)
    df["end_datetime"] = end_datetime

mask = ((df['start_datetime'] > start_datetime) & (df['end_datetime'] <= end_datetime ))
print(end_datetime)
df = df[mask]
print(df)
#df = df[start_datetime : end_datetime]
df = df.drop(columns=["start_datetime","end_datetime","id"])
df.head()


st.markdown("  ")
st.markdown("  "*10000000)

col1, col2, col3, col4 = st.columns([1, 1, 1, 1])
with col1:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index = False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv", help = "اضغط هنا لتحميل الملف", key = "download1")


with col2:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index = False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download2")

with col3:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index=False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download3")

with col4:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index=False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download4")


st.markdown("  ")
st.markdown("  "*10000000)
col5, col6, col7, col8 = st.columns([1, 1, 1, 1])
with col5:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index=False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download5")

with col6:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index=False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download6")

with col7:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index=False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download7")

with col8:
    st.image("pump_on.png")
    with st.expander("عرض البيانات"):
        st.table(df)
        csv = df.to_csv(index=False).encode("utf-8")
        st.download_button("تنزيل البيانات", data=csv, file_name="df.csv", mime="text/csv",
                           help="اضغط هنا لتحميل الملف", key="download8")














