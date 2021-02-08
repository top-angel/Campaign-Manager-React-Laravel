import React from "react"
import 'bootstrap/dist/css/bootstrap.min.css';
import AddData from "./addDate";
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom'
import EditCampaignList from "./editcamoaignlist";
import ViewCampaignList from "./viewcamoaignlist";


function App() {
  return (
    <Router>
      <div className="container">
        <Switch>
          <Route exact path="/" component={AddData} />
          <Route exact path="/editcamoaignlist/:id" component={EditCampaignList} />
          <Route exact path="/viewcamoaignlist/:id" component={ViewCampaignList} />
        </Switch>
      </div>
    </Router>
  );
}

export default App;
