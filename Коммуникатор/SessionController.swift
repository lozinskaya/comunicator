//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

extension UIImageView {
    func load(url: URL) {
        DispatchQueue.global().async { [weak self] in
            if let data = try? Data(contentsOf: url) {
                if let image = UIImage(data: data) {
                    DispatchQueue.main.async {
                        self?.image = image
                    }
                }
            }
        }
    }
}

class SessionController: UIViewController {

    @IBOutlet weak var balanceLabel: UILabel!
    @IBOutlet weak var qrView: UIImageView!
    @IBOutlet weak var tariffLabel: UILabel!
    
    @IBOutlet weak var timeLabel: UILabel!
    @IBOutlet weak var countLabel: UILabel!
    
    var timer = Timer()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        qrView.load(url: WebFuncs.ActionUrl(action: "qrcode", params: ["code":Global.userinfo["code"] as! String]));
        timer = Timer.scheduledTimer(timeInterval: 30,target: self, selector: #selector(timerAction), userInfo: nil, repeats: true)
    }
    
    @objc func timerAction() {
       WebFuncs.JSONRequest(action: "session", params: ["sessionkey": Global.sessionkey]) { result in
            debugPrint(result);
            if let result = result {
                DispatchQueue.main.async {
                    Global.sessioninfo = result;
                    Global.balance = Global.sessioninfo["balance"] as? String ?? "0"
                    self.balanceLabel.text = Global.balance + " ₽"
                }
            }
        }
    }
}
