//
//  ProximityBuy.swift
//  BeaconSDK
//
//  Created by Ewerton Pereira on 22/05/16.
//  Copyright © 2016 BoomSoft. All rights reserved.
//
import UIKit
import CoreLocation

class ProximityBuy:NSObject, CLLocationManagerDelegate {
    
    var myBeaconRegion:CLBeaconRegion?
    var locationManager:CLLocationManager?
    var lblDebug:UILabel?
    var view:UIView?
    var beaconRequested = false
    var trackId:String = ""
    var campaign:NSDictionary?
    var campaignView:UIView?
    
    private let host = "http://192.168.0.3/proximitybuy/dashboard/api/"
    
    init (trackid:String) {
        super.init()
        
        self.trackId = trackid
        
        locationManager = CLLocationManager()
        locationManager?.delegate = self
        locationManager?.requestWhenInUseAuthorization()
        locationManager?.requestAlwaysAuthorization()
        // let uuid = NSUUID(UUIDString:"AF42A9D9-5456-43A9-8DFD-8FF1F66279E0")
        
        // let uuid = NSUUID(UUIDString:"530D7B85-1B42-4AE2-8F6A-4EA5E3FB627F")
        
        let uuid = NSUUID(UUIDString:"C23B206F-DA1E-4AC4-A9F5-10B067F62683")
        //let uuid = NSUUID(UUIDString:"163EB541-B100-4BA5-8652-EB0C513FB0F4")
        
        myBeaconRegion = CLBeaconRegion(proximityUUID: uuid!,major:10,minor:1, identifier: "ewerton.beacon")
       // myBeaconRegion = CLBeaconRegion(proximityUUID: uuid!, identifier: "ewerton.beacon")
        myBeaconRegion?.notifyEntryStateOnDisplay = true
        myBeaconRegion?.notifyOnEntry = true
        myBeaconRegion?.notifyOnExit = true
        
        locationManager!.allowsBackgroundLocationUpdates = true
        locationManager!.pausesLocationUpdatesAutomatically = false
        
        locationManager?.startMonitoringForRegion(myBeaconRegion!)
    
    }
    
    func locationManager(manager: CLLocationManager, didEnterRegion region: CLRegion) {
        self.locationManager?.startRangingBeaconsInRegion(self.myBeaconRegion!)
        print("ender")
    }
    
    func locationManager(manager: CLLocationManager, didExitRegion region: CLRegion) {
        self.locationManager?.stopRangingBeaconsInRegion(self.myBeaconRegion!)
        print("Beacon NOT FOUND")
    }
    
    func locationManager(manager: CLLocationManager, didRangeBeacons beacons: [CLBeacon], inRegion region: CLBeaconRegion) {
        print("Beacon Found")
        
        let beaconFound = beacons.first
        
        if lblDebug == nil {
            lblDebug = UILabel(frame: CGRectMake(10, self.view!.frame.size.height-50,self.view!.frame.size.width-20,50))
            lblDebug?.font = UIFont(name: "HelveticaNeue", size: 11)
            self.view?.addSubview(lblDebug!)
        }
        
        if beaconFound?.proximityUUID == nil {
            lblDebug?.text = "Outside beacon area"
        }else{
            
            lblDebug!.text = "Debug Info - Distance \(beaconFound?.accuracy)-  Beacon \(beaconFound?.proximityUUID.UUIDString) - \(beaconFound?.minor.stringValue) - \(beaconFound?.major.stringValue) "
            
            if !beaconRequested && beaconFound?.accuracy < 3{
                beaconRequested = true
                loadFromApi(self.trackId, bid: beaconFound!.major.stringValue )
            }
        }
        
    }

    func loadFromApi (trackId:String, bid:String){
        let urlPath: String = self.host + "appcampaign/" + trackId + "/" + bid
        
        let url: NSURL = NSURL(string: urlPath)!
        let request1: NSURLRequest = NSURLRequest(URL: url)
        let queue:NSOperationQueue = NSOperationQueue()
        
        NSURLConnection.sendAsynchronousRequest(request1, queue: queue, completionHandler:{ (response: NSURLResponse?, data: NSData?, error: NSError?) -> Void in
            
            do {
                if data == nil {
                    return
                }
                if let jsonResult = try NSJSONSerialization.JSONObjectWithData(data!, options: []) as? NSDictionary {
                    print("ASynchronous\(jsonResult)")
                    self.campaign = jsonResult["campanha"] as? NSDictionary
                    self.loadImage(jsonResult["campanha"]!["img"] as! String)
                }
            } catch let error as NSError {
                print(error.localizedDescription)
            }
            
        })
    }
    
    func loadImage (urlPath:String){
        
        let url: NSURL = NSURL(string: urlPath)!
        let request1: NSURLRequest = NSURLRequest(URL: url)
        let queue:NSOperationQueue = NSOperationQueue()
    
        NSURLConnection.sendAsynchronousRequest(request1, queue: queue, completionHandler:{ (response: NSURLResponse?, data: NSData?, error: NSError?) -> Void in
            
            dispatch_async(dispatch_get_main_queue(),{
                
                let image = UIImage(data: data!)
                
                self.campaignView = UIView(frame: self.view!.frame)
                
                let shadow = UIView(frame: self.view!.frame)
                shadow.backgroundColor = UIColor.blackColor()
                shadow.alpha = 0.6
                self.campaignView?.addSubview(shadow)
                
                let imageView = UIImageView(image: image)
                imageView.frame.origin.x = (self.view!.frame.size.width - imageView.frame.size.width ) / 2
                imageView.frame.origin.y = (self.view!.frame.size.height - imageView.frame.size.height ) / 2
                
                let btnAction = UIButton(frame: self.view!.frame)
        
                btnAction.addTarget(self, action: #selector(ProximityBuy.openAction(_:)), forControlEvents: UIControlEvents.TouchUpInside)
                
                self.campaignView?.addSubview(imageView)
                self.campaignView?.addSubview(btnAction)
                self.view?.addSubview(self.campaignView!)
            })

        })
    }
    
    
    func openAction (sender:UIButton) {
        let url = campaign!["link"] as! String
        UIApplication.sharedApplication().openURL(NSURL(string: url)!)
    }
    
    

}
