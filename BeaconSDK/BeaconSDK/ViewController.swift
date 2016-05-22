//
//  ViewController.swift
//  BeaconSDK
//
//  Created by Ewerton Pereira on 21/05/16.
//  Copyright © 2016 BoomSoft. All rights reserved.
//

import UIKit
import CoreLocation


class ViewController: UIViewController {
    
    let proxymityBuy = ProximityBuy (trackid: "29AFD8DF-C416-4D41-8D49-36C27157E65A")

    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        proxymityBuy.view = self.view
        
        
    }

}

