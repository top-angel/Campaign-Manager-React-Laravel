import React, { useState, useEffect } from 'react'
import axios from 'axios';
import { useHistory, useParams } from 'react-router-dom'

const EditCampaignList = () => {
    const { id } = useParams();

    return (
        <>
            <h1>{id}</h1>
        </>
    )
}

export default EditCampaignList