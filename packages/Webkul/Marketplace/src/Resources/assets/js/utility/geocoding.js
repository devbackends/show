import axios from 'axios';
import {GOOGLE_GEOCODE_API_URL, GOOGLE_MAPS_API_KEY} from "../constant";

const geocoding = (address) => {
    return axios.get(GOOGLE_GEOCODE_API_URL, {
        params: {
            address,
            key: GOOGLE_MAPS_API_KEY
        }
    })
}

export default geocoding;