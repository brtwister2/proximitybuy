//
//  ViewController.swift
//  BeaconSDK
//
//  Created by Ewerton Pereira on 21/05/16.
//  Copyright © 2016 BoomSoft. All rights reserved.
//

import UIKit
import CoreLocation


class ViewController: UIViewController, CLLocationManagerDelegate {

    var myBeaconRegion:CLBeaconRegion?
    var locationManager:CLLocationManager?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        locationManager = CLLocationManager()
        locationManager?.delegate = self
        locationManager?.requestWhenInUseAuthorization()
        locationManager?.requestAlwaysAuthorization()
       // let uuid = NSUUID(UUIDString:"AF42A9D9-5456-43A9-8DFD-8FF1F66279E0")
        
       // let uuid = NSUUID(UUIDString:"530D7B85-1B42-4AE2-8F6A-4EA5E3FB627F")
        
        let uuid = NSUUID(UUIDString:"530D7B85-1B42-4AE2-8F6A-4EA5E3FB627F")
        myBeaconRegion = CLBeaconRegion(proximityUUID: uuid!,major:10,minor:1, identifier: "ewerton.beacon")
       // myBeaconRegion = CLBeaconRegion(proximityUUID: uuid!, identifier: "ewerton.beacon")
        myBeaconRegion?.notifyEntryStateOnDisplay = true
        myBeaconRegion?.notifyOnEntry = true
        
        locationManager!.allowsBackgroundLocationUpdates = true
        locationManager!.pausesLocationUpdatesAutomatically = false
        
        locationManager?.startMonitoringForRegion(myBeaconRegion!)
    }
    
    func locationManager(manager: CLLocationManager, didEnterRegion region: CLRegion) {
        self.locationManager?.startRangingBeaconsInRegion(self.myBeaconRegion!)
    }
    
    func locationManager(manager: CLLocationManager, didExitRegion region: CLRegion) {
        self.locationManager?.stopRangingBeaconsInRegion(self.myBeaconRegion!)
        debugPrint("Beacon NOT FOUND")
    }
    
    func locationManager(manager: CLLocationManager, didRangeBeacons beacons: [CLBeacon], inRegion region: CLBeaconRegion) {
        debugPrint("Beacon Found")
        
        let beaconFound = beacons.first
        
        debugPrint("Beacon \(beaconFound?.proximityUUID) - \(beaconFound?.minor) - \(beaconFound?.major)")
    }


}

