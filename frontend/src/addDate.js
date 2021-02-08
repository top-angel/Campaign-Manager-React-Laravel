import React, { useState, useEffect } from "react";
import axios from "axios";
import Modal from "react-bootstrap/Modal";
import ModalFooter from "react-bootstrap/ModalFooter";

import { Link } from "react-router-dom";

const AddData = () => {
    useEffect(() => {
        campaignlist();
    }, []);
    let baseurl = `http://dwscamp.demoproject.info/api/`;
    const [name, setName] = useState("test6");
    const [fromDate, setFromDate] = useState(new Date());
    const [toDate, setToDate] = useState(new Date());
    const [Loading, setLoading] = useState(true);
    const [totalBudget, setTotalBudget] = useState(5000);
    const [dailyBudget, setDailyBudget] = useState(200);
    const [image, setImage] = useState([]);
    const [campaignListData, setCampaignListData] = useState([]);
    const [showModal, setShowModal] = useState(false);
    let testimage = [];

    const formData = async (e) => {
        e.preventDefault();
        // let data = {
        //     name: name,
        //     from_date: fromDate,
        //     to_date: toDate,
        //     daily_budget: dailyBudget,
        //     total_budget: totalBudget,
        //     active: 1,
        //     creative_uploads: image,
        // };


        const DATA = new FormData();
        DATA.append('name', name);
        DATA.append('from_date', fromDate);
        DATA.append('to_date', toDate);
        DATA.append('daily_budget', dailyBudget);
        DATA.append('active', 1);

        DATA.append('total_budget', totalBudget);

        DATA.append('creative_uploads', [image]);
        DATA.append('creative_uploads', [image]);

        console.log(DATA);

        await axios.post(`${baseurl}campaigns`, DATA)
            .then((res) => {
                console.log(res);
            })
    };
    const campaignlist = async () => {
        setLoading(true);
        await axios
            .get(`${baseurl}campaigns`)
            .then((res) => {
                setCampaignListData(res?.data?.data);
            })
            .catch((err) => {
                alert(err);
            });
        setLoading(false);
    };
    const deleteValue = (id) => {
        console.log(id);
    };

    // const convertImageToUrl = (e) => {
    //     let data = []
    //     data.push(e.target.files)
    //     console.log(data);

    //     // data?.map(value => {
    //     //     testimage = URL.createObjectURL(value)
    //     //     setImage([...image, testimage])
    //     // })

    //     // let testimage = URL.createObjectURL(e.target.files[0])
    // }
    console.log(image);


    return (
        <>
            {!Loading && (
                <>
                    <h1>Campagin Manager</h1>
                    <form onSubmit={formData}>
                        <div className="form-row">
                            <div className="form-group col-md-6">
                                <label htmlFor="name">Name</label>
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder="Enter Your Name"
                                    onChange={(e) => setName(e.target.value)}
                                    value={name}
                                />
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group col-md-6">
                                <label htmlFor="from Date">From Date</label>
                                <input
                                    type="date"
                                    className="form-control"
                                    onChange={(e) => setFromDate(e.target.value)}
                                    value={fromDate}
                                />
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group col-md-6">
                                <label htmlFor="toDate">To Date</label>
                                <input
                                    type="date"
                                    className="form-control"
                                    onChange={(e) => setToDate(e.target.value)}
                                    value={toDate}
                                />
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group col-md-6">
                                <label htmlFor="Total budget">
                                    Total Budget (in USD) <span style={{ color: "red" }}>*</span>
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    className="form-control"
                                    placeholder="Enter Total Budget "
                                    value={totalBudget}

                                    onChange={(e) =>
                                        setTotalBudget(Number(e.target.value).toFixed(2))
                                    }
                                />
                            </div>
                        </div>

                        <div className="form-row">
                            <div className="form-group col-md-6">
                                <label htmlFor="Total budget">
                                    Daily Budget (in USD) <span style={{ color: "red" }}>*</span>
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    className="form-control"
                                    placeholder="Daily Budget "
                                    value={dailyBudget}
                                    onChange={(e) =>
                                        setDailyBudget(Number(e.target.value).toFixed(2))
                                    }
                                />
                            </div>
                        </div>
                        <div className="form-group">
                            <label htmlFor="Creative upload">Creative upload</label>
                            <input
                                type="file"
                                className="form-control-file"
                                multiple
                                onChange={(e) => setImage(e.target.files[0])}
                            // onChange={convertImageToUrl}
                            // onChange={(e) => setImage(URL.createObjectURL(e.target.files[0]))}

                            />
                        </div>

                        <button type="submit" className="btn btn-primary">
                            Submit
            </button>
                    </form>

                    <>

                        <Modal
                            show={showModal}
                            onHide={() => setShowModal(false)}
                            backdrop="static"
                            keyboard={false}
                            size="lg"
                            aria-labelledby="example-modal-sizes-title-lg"
                        >
                            <Modal.Header closeButton>
                                <Modal.Title id="example-modal-sizes-title-lg">Modal title</Modal.Title>
                            </Modal.Header>
                            <Modal.Body>

                            </Modal.Body>
                            <Modal.Footer>
                                <button variant="secondary" onClick={() => setShowModal(false)}>
                                    Close
                </button>
                            </Modal.Footer>
                        </Modal>

                    </>

                    <div>
                        {campaignListData?.length !== 0 && (
                            <table className="table mt-5">
                                <thead className="thead-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">From Date</th>
                                        <th scope="col">To Date</th>
                                        <th scope="col">Daily Budget</th>
                                        <th scope="col">Total Budget</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {campaignListData?.map(
                                        ({
                                            id,
                                            name,
                                            from_date,
                                            to_date,
                                            daily_budget,
                                            total_budget,
                                        }) => (
                                            <tr key={id}>
                                                <td scope="row">{name}</td>
                                                <td scope="row">{from_date}</td>
                                                <td scope="row">{to_date}</td>
                                                <td scope="row">{daily_budget}</td>
                                                <td scope="row">{total_budget}</td>

                                                <td>
                                                    <Link to={`/editcamoaignlist/${id}`}>Edit</Link> |
                                                    <Link
                                                        to="#"
                                                        onClick={() => {
                                                            deleteValue(id);
                                                        }}
                                                    >
                                                        Delete
                          </Link>
                          |
                                                    <Link to="#" onClick={() => setShowModal(true)}>
                                                        Preview
                          </Link>
                                                </td>
                                            </tr>
                                        )
                                    )}
                                </tbody>
                            </table>
                        )}
                    </div>
                </>
            )}
        </>
    );
};
export default AddData;
