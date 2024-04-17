import React, { useState, useEffect } from "react";
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';
import axios from "axios";

const Widget = () => {

    const [Apidata, setApiData] = useState();

    const url = `${appLocalizer.apiUrl}/wpwr/v2/settings`;

    useEffect(() => {
        axios.get(url).then((res) => {
            setApiData(res.data);
            console.log(res.data);
        });
    }, []);

    const changeFilter = (eve) => {
        const furl = `${appLocalizer.apiUrl}/wpwr/v2/last-n-days/${eve.target.value}`;
        axios.get(furl).then((res) => {
            setApiData(res.data);
            console.log(res.data);
        });
        console.log(eve.target.value);
    };
    const changeDate = (eve) => {
        const durl = `${appLocalizer.apiUrl}/wpwr/v2/date-range/${eve.target.value}`;
        axios.get(durl).then((res) => {
            setApiData(res.data);
            console.log(res.data);
        });
        console.log(eve.target.value);
    };
    const data = Apidata;

    return (
        <div>
            <div style={{ float: "left" }} className={'site-health-progress-label mb-1'}>WP React</div>
            <div style={{ float: "right" }}>
                <select onChange={changeFilter}>
                    <option value="7">Last 7 Days</option>
                    <option value="15">Last 15 Days</option>
                    <option value="30">Last 1 Month</option>
                </select>
                <input onChange={changeDate} type="date" placeholder="Select Date" />
            </div>
            <div style={{ overflow: "hidden" }}></div>
            <LineChart
                width={350}
                height={300}
                data={data}
                margin={{
                    top: 5,
                    right: 30,
                    left: 20,
                    bottom: 5,
                }}
            >
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="name" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Line type="monotone" dataKey="pv" stroke="#8884d8" activeDot={{ r: 8 }} />
                <Line type="monotone" dataKey="uv" stroke="#82ca9d" />
            </LineChart>
        </div>
    );
};

export default Widget;